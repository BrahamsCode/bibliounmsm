<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalBooks = Book::count();
        $availableBooks = Book::sum('available_quantity');
        $activeLoans = Loan::where('status', 'active')->count();
        $overdueLoans = Loan::where('status', 'active')
            ->where('due_date', '<', Carbon::now())
            ->count();

        $recentBooks = Book::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentLoans = Loan::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalBooks',
            'availableBooks',
            'activeLoans',
            'overdueLoans',
            'recentBooks',
            'recentLoans'
        ));
    }
}
