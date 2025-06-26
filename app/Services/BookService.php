<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



/**
 * Class BookService
 * Handles book-related operations such as filtering, searching, and retrieving categories.
 */

class BookService
{
    public function getFilteredBooks(Request $request)
    {
        $query = Book::with('category');

        $this->applySearchFilter($query, $request->get('search'));
        $this->applyCategoryFilter($query, $request->get('category'));
        $this->applyAvailabilityFilter($query, $request->get('available'));

        return $query->orderBy('title')->paginate(12);
    }

    public function getAllCategories()
    {
        return Category::orderBy('name')->get();
    }

    private function applySearchFilter($query, ?string $search): void
    {
        if (!$search) {
            return;
        }

        $query->where(function ($q) use ($search) {
            $q->where('title', 'ILIKE', "%{$search}%")
                ->orWhere('author', 'ILIKE', "%{$search}%")
                ->orWhere('isbn', 'ILIKE', "%{$search}%");
        });
    }

    private function applyCategoryFilter($query, ?string $categoryId): void
    {
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
    }

    private function applyAvailabilityFilter($query, ?string $available): void
    {
        if ($available === '1') {
            $query->where('available_quantity', '>', 0);
        }
    }
}
