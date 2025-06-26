<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                    ->orWhere('author', 'ILIKE', "%{$search}%")
                    ->orWhere('isbn', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->has('category')) {
            $query->where('category_id', $request->get('category'));
        }

        $books = $query->paginate(12);
        $categories = Category::all();

        return view('books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $book->load('category', 'activeLoans.user');
        return view('books.show', compact('book'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books',
            'description' => 'nullable|string',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'pages' => 'nullable|integer',
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

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'description' => 'nullable|string',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'pages' => 'nullable|integer',
            'language' => 'required|string',
            'stock_quantity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')
            ->with('success', 'Libro actualizado exitosamente.');
    }

    public function destroy(Book $book)
    {
        if ($book->activeLoans()->count() > 0) {
            return redirect()->route('books.index')
                ->with('error', 'No se puede eliminar un libro con préstamos activos.');
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Libro eliminado exitosamente.');
    }
}
