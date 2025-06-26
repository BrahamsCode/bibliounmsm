<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de libros
    Route::resource('books', BookController::class);

    // Rutas de préstamos
    Route::resource('loans', LoanController::class);
    Route::post('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
});

// Rutas públicas del catálogo
Route::get('/catalogo', [BookController::class, 'index'])->name('catalog');
Route::get('/catalogo/{book}', [BookController::class, 'show'])->name('catalog.show');
