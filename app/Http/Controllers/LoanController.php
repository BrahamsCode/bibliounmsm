<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of loans
     */
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'book.category']);

        // Si es estudiante, solo mostrar sus préstamos
        if (Auth::check() && Auth::user()->isStudent()) {
            $query->where('user_id', Auth::id());
        }

        // Filtro por estado
        if ($request->has('status') && $request->get('status') !== '') {
            $status = $request->get('status');

            if ($status === 'overdue') {
                $query->where('status', 'active')
                      ->where('due_date', '<', Carbon::now());
            } else {
                $query->where('status', $status);
            }
        }

        // Filtro por búsqueda (solo para admin/bibliotecario)
        if ($request->has('search') && $request->get('search') !== '' &&
            Auth::check() && !Auth::user()->isStudent()) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'ILIKE', "%{$search}%")
                             ->orWhere('student_code', 'ILIKE', "%{$search}%");
                })->orWhereHas('book', function ($bookQuery) use ($search) {
                    $bookQuery->where('title', 'ILIKE', "%{$search}%");
                })->orWhere('loan_code', 'ILIKE', "%{$search}%");
            });
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new loan (admin/librarian only)
     */
    public function create(Request $request)
    {
        // Solo admin y bibliotecarios pueden crear préstamos manualmente
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('books.index')
                ->with('error', 'Los estudiantes deben solicitar préstamos desde el catálogo de libros.');
        }

        $books = Book::with('category')->available()->orderBy('title')->get();
        $users = User::where('role', 'student')->orderBy('name')->get();

        // Pre-seleccionar libro si viene en la URL
        $selectedBookId = $request->get('book_id');
        $selectedUserId = $request->get('user_id');

        return view('loans.create', compact('books', 'users', 'selectedBookId', 'selectedUserId'));
    }

    /**
     * Store a newly created loan (admin/librarian only)
     */
    public function store(Request $request)
    {
        // Solo admin y bibliotecarios pueden crear préstamos manualmente
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('books.index')
                ->with('error', 'Los estudiantes deben solicitar préstamos desde el catálogo de libros.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after:loan_date',
            'notes' => 'nullable|string|max:500',
        ]);

        $book = Book::find($validated['book_id']);
        $user = User::find($validated['user_id']);

        // Validaciones
        if (!$book->isAvailable()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'El libro no está disponible para préstamo.');
        }

        // Verificar que el usuario no tenga ya este libro
        $existingLoan = $book->activeLoans()
            ->where('user_id', $user->id)
            ->exists();

        if ($existingLoan) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'El usuario ya tiene este libro en préstamo.');
        }

        // Verificar límite de préstamos del usuario
        $activeLoansCount = $user->activeLoans()->count();
        if ($activeLoansCount >= 3) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'El usuario ha alcanzado el límite máximo de 3 préstamos activos.');
        }

        // Crear el préstamo
        $loan = Loan::create([
            'user_id' => $validated['user_id'],
            'book_id' => $validated['book_id'],
            'loan_date' => $validated['loan_date'],
            'due_date' => $validated['due_date'],
            'status' => 'active',
            'notes' => $validated['notes'],
        ]);

        // Reducir cantidad disponible
        $book->decrement('available_quantity');

        return redirect()->route('loans.index')
            ->with('success', "Préstamo creado exitosamente. Código: {$loan->loan_code}");
    }

    /**
     * Display the specified loan
     */
    public function show(Loan $loan)
    {
        // Los estudiantes solo pueden ver sus propios préstamos
        if (Auth::check() && Auth::user()->isStudent() && $loan->user_id !== Auth::id()) {
            return redirect()->route('loans.index')
                ->with('error', 'No tienes permiso para ver este préstamo.');
        }

        $loan->load('user', 'book.category');
        return view('loans.show', compact('loan'));
    }

    /**
     * Return a book (admin/librarian only)
     */
    public function return(Loan $loan)
    {
        // Solo admin y bibliotecarios pueden marcar devoluciones
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->back()
                ->with('error', 'Solo el personal de la biblioteca puede marcar devoluciones.');
        }

        if ($loan->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Este préstamo ya fue devuelto.');
        }

        $loan->update([
            'return_date' => Carbon::now(),
            'status' => 'returned'
        ]);

        // Incrementar cantidad disponible
        $loan->book->increment('available_quantity');

        return redirect()->route('loans.index')
            ->with('success', 'Libro devuelto exitosamente.');
    }

    /**
     * Get loan statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'active' => Loan::where('status', 'active')->count(),
            'returned_today' => Loan::where('status', 'returned')
                ->whereDate('return_date', today())->count(),
            'overdue' => Loan::where('status', 'active')
                ->where('due_date', '<', Carbon::now())->count(),
            'this_month' => Loan::whereBetween('loan_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Extend loan due date (for renewals)
     */
    public function extend(Loan $loan, Request $request)
    {
        // Solo admin y bibliotecarios pueden extender préstamos
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->back()
                ->with('error', 'Contacta al personal de la biblioteca para renovar tu préstamo.');
        }

        if ($loan->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Solo se pueden extender préstamos activos.');
        }

        $validated = $request->validate([
            'new_due_date' => 'required|date|after:' . $loan->due_date->format('Y-m-d'),
            'extension_reason' => 'nullable|string|max:500',
        ]);

        $loan->update([
            'due_date' => $validated['new_due_date'],
            'notes' => $loan->notes . "\n\nExtensión: " . ($validated['extension_reason'] ?? 'Sin motivo especificado') . " - " . now()->format('d/m/Y H:i'),
        ]);

        return redirect()->back()
            ->with('success', 'Préstamo extendido hasta ' . Carbon::parse($validated['new_due_date'])->format('d/m/Y'));
    }
}
