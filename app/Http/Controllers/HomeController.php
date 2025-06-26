<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stats = [
            'uptime' => '99.5%',
            'response_time' => '1.8s',
            'accuracy' => '99%',
            'active_users' => User::count(),
        ];

        $recentBooks = Book::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentLoans = Loan::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('home', compact('stats', 'recentBooks', 'recentLoans'));
    }
}
