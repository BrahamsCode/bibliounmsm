<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ruta principal - Página de inicio
Route::get('/', [HomeController::class, 'index'])->name('home');

// Ruta para Laravel UI (después del login/registro)
Route::get('/home', [HomeController::class, 'dashboard'])->name('home.dashboard');

// Rutas de autenticación (Laravel UI)
Auth::routes();

// Rutas públicas del catálogo (accesibles sin autenticación)
Route::get('/catalogo', [BookController::class, 'index'])->name('catalog');
Route::get('/catalogo/{book}', [BookController::class, 'show'])->name('catalog.show');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas por Autenticación
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard principal (temporal - redirige a books)
    Route::get('/dashboard', function () {
        return redirect()->route('books.index');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Gestión de Libros
    |--------------------------------------------------------------------------
    */
    Route::resource('books', BookController::class);

    /*
    |--------------------------------------------------------------------------
    | Gestión de Préstamos
    |--------------------------------------------------------------------------
    */
    Route::resource('loans', LoanController::class);
    Route::post('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');

    /*
    |--------------------------------------------------------------------------
    | Rutas temporales para el menú (redirigen a books por ahora)
    |--------------------------------------------------------------------------
    */
    Route::get('/categories', function () {
        return redirect()->route('books.index')->with('info', 'Módulo de categorías en desarrollo');
    })->name('categories.index');

    Route::get('/users', function () {
        return redirect()->route('books.index')->with('info', 'Módulo de usuarios en desarrollo');
    })->name('users.index');

    Route::get('/reports', function () {
        return redirect()->route('books.index')->with('info', 'Módulo de reportes en desarrollo');
    })->name('reports.index');

    /*
    |--------------------------------------------------------------------------
    | Perfil de Usuario (temporal)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile/edit', function () {
        return view('profile.edit', ['user' => auth()->user()]);
    })->name('profile.edit');

    /*
    |--------------------------------------------------------------------------
    | API para estadísticas (AJAX)
    |--------------------------------------------------------------------------
    */
    Route::get('/api/stats', [HomeController::class, 'stats'])->name('api.stats');
});

/*
|--------------------------------------------------------------------------
| Ruta de fallback para páginas no encontradas
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});
