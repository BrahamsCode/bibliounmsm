<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Estadísticas del sistema
        $stats = [
            'uptime' => '99.9%',
            'response_time' => '1.2s',
            'accuracy' => '98.7%',
            'active_users' => User::whereDate('updated_at', '>=', Carbon::now()->subDays(7))->count()
        ];

        // Libros recientes (últimos 6 agregados)
        $recentBooks = Book::with('category')
            ->latest()
            ->take(6)
            ->get();

        // Préstamos recientes (últimos 6 activos)
        $recentLoans = Loan::with(['book', 'user'])
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        // Estadísticas adicionales para la vista
        $totalBooks = Book::count();
        $activeLoans = Loan::where('status', 'active')->count();
        $digitalResources = Book::whereNotNull('cover_image')->count(); // Simulamos recursos digitales
        $registeredUsers = User::count();

        return view('home', compact(
            'stats',
            'recentBooks',
            'recentLoans',
            'totalBooks',
            'activeLoans',
            'digitalResources',
            'registeredUsers'
        ));
    }

    /**
     * Show the user dashboard after login/registration.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        // Libros recientes para mostrar al usuario
        $recentBooks = Book::with('category')
            ->latest()
            ->take(8)
            ->get();

        // Si el usuario no está autenticado, puede ser que haya cerrado sesión
        // y llegue aquí, así que manejamos ambos casos
        return view('home', compact('recentBooks'));
    }
    /*
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function stats()
    {
        $stats = [
            'uptime' => '99.9%',
            'response_time' => round(rand(80, 150) / 100, 1) . 's',
            'accuracy' => rand(975, 999) / 10 . '%',
            'active_users' => User::whereDate('updated_at', '>=', Carbon::now()->subDays(7))->count(),
            'total_books' => Book::count(),
            'active_loans' => Loan::where('status', 'active')->count(),
            'overdue_loans' => Loan::where('status', 'active')
                ->where('due_date', '<', Carbon::now())
                ->count(),
            'available_books' => Book::where('available_quantity', '>', 0)->count()
        ];

        return response()->json($stats);
    }
}
