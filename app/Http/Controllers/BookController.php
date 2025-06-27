<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display a listing of books (public and authenticated users)
     */
    public function index(Request $request)
    {
        $query = Book::with('category');

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                    ->orWhere('author', 'ILIKE', "%{$search}%")
                    ->orWhere('isbn', 'ILIKE', "%{$search}%");
            });
        }

        // Filtro por categoría
        if ($request->has('category') && $request->get('category') != '') {
            $query->where('category_id', $request->get('category'));
        }

        // Filtro por disponibilidad
        if ($request->has('available') && $request->get('available') == '1') {
            $query->where('available_quantity', '>', 0);
        }

        $books = $query->orderBy('title')->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Show book details with loan option for students
     */
    public function show(Book $book)
    {
        $book->load('category', 'activeLoans.user');

        // Verificar si el usuario actual ya tiene este libro prestado
        $userHasActiveLoan = false;
        if (Auth::check()) {
            $userHasActiveLoan = $book->activeLoans()
                ->where('user_id', Auth::id())
                ->exists();
        }

        return view('books.show', compact('book', 'userHasActiveLoan'));
    }

    /**
     * Process book loan request from student
     */
    public function requestLoan(Request $request, Book $book)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para solicitar un préstamo.');
        }

        $user = Auth::user();

        // Verificar que el usuario sea estudiante
        if (!$user->isStudent()) {
            return redirect()->back()
                ->with('error', 'Solo los estudiantes pueden solicitar préstamos.');
        }

        // Verificar que el libro esté disponible
        if (!$book->isAvailable()) {
            return redirect()->back()
                ->with('error', 'Este libro no está disponible para préstamo.');
        }

        // Verificar que el usuario no tenga ya este libro prestado
        $existingLoan = $book->activeLoans()
            ->where('user_id', $user->id)
            ->exists();

        if ($existingLoan) {
            return redirect()->back()
                ->with('error', 'Ya tienes este libro en préstamo.');
        }

        // Verificar límite de préstamos activos por usuario (máximo 3)
        $activeLoansCount = $user->activeLoans()->count();
        if ($activeLoansCount >= 3) {
            return redirect()->back()
                ->with('error', 'Has alcanzado el límite máximo de 3 préstamos activos.');
        }

        // Crear el préstamo
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(14), // 2 semanas de préstamo
            'status' => 'active',
            'notes' => $request->input('notes', ''),
        ]);

        // Reducir cantidad disponible
        $book->decrement('available_quantity');

        return redirect()->route('books.show', $book)
            ->with('success', "¡Préstamo realizado exitosamente! Tu préstamo vence el {$loan->due_date->format('d/m/Y')}.");
    }

    /**
     * Admin/Librarian: Create new book
     */
    public function create()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            abort(403, 'No tienes permisos para crear libros.');
        }

        $categories = Category::orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    /**
     * Admin/Librarian: Store new book
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            abort(403, 'No tienes permisos para crear libros.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books',
            'description' => 'nullable|string',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'pages' => 'nullable|integer|min:1',
            'language' => 'required|string',
            'stock_quantity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $validated['available_quantity'] = $validated['stock_quantity'];

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Libro creado exitosamente.');
    }

    /**
     * Admin/Librarian: Edit book
     */
    public function edit(Book $book)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            abort(403, 'No tienes permisos para editar libros.');
        }

        $categories = Category::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Admin/Librarian: Update book
     */
    public function update(Request $request, Book $book)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            abort(403, 'No tienes permisos para actualizar libros.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'description' => 'nullable|string',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'pages' => 'nullable|integer|min:1',
            'language' => 'required|string',
            'stock_quantity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book->update($validated);

        return redirect()->route('books.show', $book)
            ->with('success', 'Libro actualizado exitosamente.');
    }

    /**
     * Admin/Librarian: Delete book
     */
    public function destroy(Book $book)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            abort(403, 'No tienes permisos para eliminar libros.');
        }

        if ($book->activeLoans()->count() > 0) {
            return redirect()->route('books.index')
                ->with('error', 'No se puede eliminar un libro con préstamos activos.');
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Libro eliminado exitosamente.');
    }

    /**
     * Filter books by category (public route)
     */
    public function byCategory(Category $category, Request $request)
    {
        $request->merge(['category' => $category->id]);
        return $this->index($request);
    }
}
