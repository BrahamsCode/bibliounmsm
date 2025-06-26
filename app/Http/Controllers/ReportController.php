<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard
     */
    public function index()
    {
        // Solo admin y bibliotecarios pueden ver reportes
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('books.index')
                ->with('error', 'No tienes permisos para acceder a los reportes.');
        }

        return view('reports.index');
    }

    /**
     * Reporte de préstamos
     */
    public function loans(Request $request)
    {
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('books.index')
                ->with('error', 'No tienes permisos para acceder a los reportes.');
        }

        $startDate = $request->get('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $status = $request->get('status', 'all');

        $query = Loan::with(['user', 'book.category'])
            ->whereBetween('loan_date', [$startDate, $endDate]);

        if ($status !== 'all') {
            if ($status === 'overdue') {
                $query->where('status', 'active')
                      ->where('due_date', '<', Carbon::now());
            } else {
                $query->where('status', $status);
            }
        }

        $loans = $query->orderBy('loan_date', 'desc')->get();

        // Estadísticas del período
        $stats = [
            'total_loans' => $loans->count(),
            'active_loans' => $loans->where('status', 'active')->count(),
            'returned_loans' => $loans->where('status', 'returned')->count(),
            'overdue_loans' => $loans->where('status', 'active')
                ->where('due_date', '<', Carbon::now())->count(),
        ];

        return view('reports.loans', compact('loans', 'stats', 'startDate', 'endDate', 'status'));
    }

    /**
     * Reporte de libros más prestados
     */
    public function popularBooks(Request $request)
    {
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('books.index')
                ->with('error', 'No tienes permisos para acceder a los reportes.');
        }

        $startDate = $request->get('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $limit = $request->get('limit', 20);

        $popularBooks = Book::with(['category'])
            ->select('books.*', DB::raw('COUNT(loans.id) as loans_count'))
            ->leftJoin('loans', 'books.id', '=', 'loans.book_id')
            ->whereBetween('loans.loan_date', [$startDate, $endDate])
            ->groupBy('books.id')
            ->orderBy('loans_count', 'desc')
            ->limit($limit)
            ->get();

        return view('reports.popular-books', compact('popularBooks', 'startDate', 'endDate', 'limit'));
    }

    /**
     * Reporte de usuarios activos
     */
    public function activeUsers(Request $request)
    {
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('books.index')
                ->with('error', 'No tienes permisos para acceder a los reportes.');
        }

        $startDate = $request->get('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $limit = $request->get('limit', 20);

        $activeUsers = User::with(['loans' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('loan_date', [$startDate, $endDate]);
            }])
            ->select('users.*', DB::raw('COUNT(loans.id) as loans_count'))
            ->leftJoin('loans', 'users.id', '=', 'loans.user_id')
            ->where('users.role', 'student')
            ->whereBetween('loans.loan_date', [$startDate, $endDate])
            ->groupBy('users.id')
            ->orderBy('loans_count', 'desc')
            ->limit($limit)
            ->get();

        return view('reports.active-users', compact('activeUsers', 'startDate', 'endDate', 'limit'));
    }

    /**
     * Reporte de categorías más prestadas
     */
    public function categories(Request $request)
    {
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('books.index')
                ->with('error', 'No tienes permisos para acceder a los reportes.');
        }

        $startDate = $request->get('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $categories = Category::with(['books'])
            ->select('categories.*', DB::raw('COUNT(loans.id) as loans_count'))
            ->leftJoin('books', 'categories.id', '=', 'books.category_id')
            ->leftJoin('loans', 'books.id', '=', 'loans.book_id')
            ->whereBetween('loans.loan_date', [$startDate, $endDate])
            ->groupBy('categories.id')
            ->orderBy('loans_count', 'desc')
            ->get();

        return view('reports.categories', compact('categories', 'startDate', 'endDate'));
    }

    /**
     * Reporte de préstamos vencidos
     */
    public function overdueLoans()
    {
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('books.index')
                ->with('error', 'No tienes permisos para acceder a los reportes.');
        }

        $overdueLoans = Loan::with(['user', 'book.category'])
            ->where('status', 'active')
            ->where('due_date', '<', Carbon::now())
            ->orderBy('due_date', 'asc')
            ->get();

        // Agrupar por días de retraso
        $overdueStats = [
            '1-7_days' => $overdueLoans->filter(function($loan) {
                return $loan->due_date->diffInDays(Carbon::now()) <= 7;
            })->count(),
            '8-15_days' => $overdueLoans->filter(function($loan) {
                $days = $loan->due_date->diffInDays(Carbon::now());
                return $days > 7 && $days <= 15;
            })->count(),
            '16-30_days' => $overdueLoans->filter(function($loan) {
                $days = $loan->due_date->diffInDays(Carbon::now());
                return $days > 15 && $days <= 30;
            })->count(),
            'more_30_days' => $overdueLoans->filter(function($loan) {
                return $loan->due_date->diffInDays(Carbon::now()) > 30;
            })->count(),
        ];

        return view('reports.overdue-loans', compact('overdueLoans', 'overdueStats'));
    }

    /**
     * Generar reporte de inventario
     */
    public function inventory(Request $request)
    {
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('books.index')
                ->with('error', 'No tienes permisos para acceder a los reportes.');
        }

        $categoryId = $request->get('category_id');
        $availability = $request->get('availability', 'all');

        $query = Book::with(['category']);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($availability === 'available') {
            $query->where('available_quantity', '>', 0);
        } elseif ($availability === 'unavailable') {
            $query->where('available_quantity', '=', 0);
        }

        $books = $query->orderBy('title')->get();
        $categories = Category::orderBy('name')->get();

        $stats = [
            'total_books' => $books->count(),
            'available_books' => $books->where('available_quantity', '>', 0)->count(),
            'unavailable_books' => $books->where('available_quantity', '=', 0)->count(),
            'total_stock' => $books->sum('stock_quantity'),
            'available_stock' => $books->sum('available_quantity'),
        ];

        return view('reports.inventory', compact('books', 'categories', 'stats', 'categoryId', 'availability'));
    }

    /**
     * Exportar reporte a CSV
     */
    public function export(Request $request)
    {
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('books.index')
                ->with('error', 'No tienes permisos para exportar reportes.');
        }

        $type = $request->get('type');
        $startDate = $request->get('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        switch ($type) {
            case 'loans':
                return $this->exportLoans($startDate, $endDate);
            case 'inventory':
                return $this->exportInventory();
            case 'overdue':
                return $this->exportOverdueLoans();
            default:
                return redirect()->back()->with('error', 'Tipo de reporte no válido.');
        }
    }

    /**
     * Exportar préstamos a CSV
     */
    private function exportLoans($startDate, $endDate)
    {
        $loans = Loan::with(['user', 'book.category'])
            ->whereBetween('loan_date', [$startDate, $endDate])
            ->orderBy('loan_date', 'desc')
            ->get();

        $filename = 'prestamos_' . $startDate . '_' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($loans) {
            $file = fopen('php://output', 'w');

            // Escribir BOM para UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Encabezados
            fputcsv($file, [
                'Código Préstamo',
                'Usuario',
                'Código Estudiante',
                'Libro',
                'Categoría',
                'Fecha Préstamo',
                'Fecha Vencimiento',
                'Fecha Devolución',
                'Estado',
                'Días de Retraso',
                'Notas'
            ]);

            foreach ($loans as $loan) {
                $daysOverdue = '';
                if ($loan->status === 'active' && $loan->due_date < Carbon::now()) {
                    $daysOverdue = $loan->due_date->diffInDays(Carbon::now());
                }

                fputcsv($file, [
                    $loan->loan_code,
                    $loan->user->name,
                    $loan->user->student_code ?? '',
                    $loan->book->title,
                    $loan->book->category->name ?? '',
                    $loan->loan_date->format('d/m/Y'),
                    $loan->due_date->format('d/m/Y'),
                    $loan->return_date ? $loan->return_date->format('d/m/Y') : '',
                    $loan->status === 'active' ? 'Activo' : 'Devuelto',
                    $daysOverdue,
                    $loan->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar inventario a CSV
     */
    private function exportInventory()
    {
        $books = Book::with(['category'])->orderBy('title')->get();
        $filename = 'inventario_' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($books) {
            $file = fopen('php://output', 'w');

            // Escribir BOM para UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Encabezados
            fputcsv($file, [
                'Título',
                'Autor',
                'ISBN',
                'Categoría',
                'Editorial',
                'Año Publicación',
                'Páginas',
                'Idioma',
                'Stock Total',
                'Disponible',
                'En Préstamo',
                'Ubicación'
            ]);

            foreach ($books as $book) {
                fputcsv($file, [
                    $book->title,
                    $book->author,
                    $book->isbn,
                    $book->category->name ?? '',
                    $book->publisher,
                    $book->publication_date ? $book->publication_date->format('Y') : '',
                    $book->pages,
                    $book->language,
                    $book->stock_quantity,
                    $book->available_quantity,
                    $book->stock_quantity - $book->available_quantity,
                    $book->location
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar préstamos vencidos a CSV
     */
    private function exportOverdueLoans()
    {
        $loans = Loan::with(['user', 'book.category'])
            ->where('status', 'active')
            ->where('due_date', '<', Carbon::now())
            ->orderBy('due_date', 'asc')
            ->get();

        $filename = 'prestamos_vencidos_' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($loans) {
            $file = fopen('php://output', 'w');

            // Escribir BOM para UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Encabezados
            fputcsv($file, [
                'Código Préstamo',
                'Usuario',
                'Código Estudiante',
                'Email',
                'Libro',
                'Categoría',
                'Fecha Préstamo',
                'Fecha Vencimiento',
                'Días de Retraso',
                'Notas'
            ]);

            foreach ($loans as $loan) {
                fputcsv($file, [
                    $loan->loan_code,
                    $loan->user->name,
                    $loan->user->student_code ?? '',
                    $loan->user->email,
                    $loan->book->title,
                    $loan->book->category->name ?? '',
                    $loan->loan_date->format('d/m/Y'),
                    $loan->due_date->format('d/m/Y'),
                    $loan->due_date->diffInDays(Carbon::now()),
                    $loan->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
