<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;

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
    Route::get('/dashboard', function() {
        return redirect()->route('books.index');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Gestión de Libros
    |--------------------------------------------------------------------------
    */
    Route::resource('books', BookController::class);

    // Ruta para solicitar préstamo (estudiantes)
    Route::post('/books/{book}/request-loan', [BookController::class, 'requestLoan'])->name('books.request-loan');

    /*
    |--------------------------------------------------------------------------
    | Gestión de Préstamos
    |--------------------------------------------------------------------------
    */
    Route::resource('loans', LoanController::class);
    Route::post('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
    Route::post('/loans/{loan}/extend', [LoanController::class, 'extend'])->name('loans.extend');

    /*
    |--------------------------------------------------------------------------
    | Gestión de Categorías (Solo Admin/Bibliotecarios)
    |--------------------------------------------------------------------------
    */
    Route::resource('categories', CategoryController::class);

    /*
    |--------------------------------------------------------------------------
    | Módulo de Reportes (Solo Admin/Bibliotecarios)
    |--------------------------------------------------------------------------
    */
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');

        // Reportes específicos
        Route::get('/loans', [ReportController::class, 'loans'])->name('loans');
        Route::get('/popular-books', [ReportController::class, 'popularBooks'])->name('popular-books');
        Route::get('/active-users', [ReportController::class, 'activeUsers'])->name('active-users');
        Route::get('/categories', [ReportController::class, 'categories'])->name('categories');
        Route::get('/overdue-loans', [ReportController::class, 'overdueLoans'])->name('overdue-loans');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');

        // Exportación de reportes
        Route::get('/export', [ReportController::class, 'export'])->name('export');
    });

    /*
    |--------------------------------------------------------------------------
    | Usuarios (temporal)
    |--------------------------------------------------------------------------
    */
    Route::get('/users', function() {
        return redirect()->route('books.index')->with('info', 'Módulo de usuarios en desarrollo');
    })->name('users.index');

    /*
    |--------------------------------------------------------------------------
    | Perfil de Usuario (temporal)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile/edit', function() {
        return view('profile.edit', ['user' => auth()->user()]);
    })->name('profile.edit');

    /*
    |--------------------------------------------------------------------------
    | API para estadísticas (AJAX)
    |--------------------------------------------------------------------------
    */
    Route::get('/api/stats', [HomeController::class, 'stats'])->name('api.stats');
    Route::get('/api/loans/stats', [LoanController::class, 'getStats'])->name('api.loans.stats');

});

/*
|--------------------------------------------------------------------------
| Ruta de fallback para páginas no encontradas
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});
