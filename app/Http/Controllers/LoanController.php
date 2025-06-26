<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'book']);

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('student_code', 'ILIKE', "%{$search}%");
            })->orWhereHas('book', function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%");
            });
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $books = Book::available()->get();
        $users = User::where('role', 'student')->get();
        return view('loans.create', compact('books', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after:loan_date',
            'notes' => 'nullable|string',
        ]);

        $book = Book::find($validated['book_id']);

        if (!$book->isAvailable()) {
            return redirect()->back()
                ->with('error', 'El libro no está disponible para préstamo.');
        }

        $loan = Loan::create($validated);

        // Reducir cantidad disponible
        $book->decrement('available_quantity');

        return redirect()->route('loans.index')
            ->with('success', 'Préstamo creado exitosamente.');
    }

    public function show(Loan $loan)
    {
        $loan->load('user', 'book.category');
        return view('loans.show', compact('loan'));
    }

    public function return(Loan $loan)
    {
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
}
