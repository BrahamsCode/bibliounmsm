@extends('layouts.app')

@section('page_title', 'Panel de Usuario')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-house-door me-2"></i>
                        ¡Bienvenido al Sistema BiblioUSMP!
                    </h5>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="welcome-content">
                        @auth
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="text-primary mb-3">¡Hola, {{ Auth::user()->name }}!</h4>
                                <p class="lead">Tu registro ha sido exitoso. Ahora puedes acceder a todos los servicios de la biblioteca digital de la Universidad San Martín de Porres.</p>

                                <div class="features-list mb-4">
                                    <h6 class="fw-semibold mb-3">¿Qué puedes hacer ahora?</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            Explorar nuestro catálogo de más de 50,000 libros
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            Realizar préstamos de libros físicos y digitales
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            Renovar y gestionar tus préstamos activos
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            Acceder a recursos académicos especializados
                                        </li>
                                    </ul>
                                </div>

                                <div class="action-buttons">
                                    <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg me-3">
                                        <i class="bi bi-book me-2"></i>
                                        Explorar Catálogo
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-lg">
                                        <i class="bi bi-person-gear me-2"></i>
                                        Completar Perfil
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="welcome-illustration">
                                    <div class="user-avatar-xl">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div class="user-info mt-3">
                                        <h6 class="fw-semibold">{{ Auth::user()->name }}</h6>
                                        <p class="text-muted small mb-2">{{ Auth::user()->email }}</p>
                                        @if(Auth::user()->student_code)
                                            <span class="badge bg-primary">{{ Auth::user()->student_code }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="guest-content py-5">
                                    <i class="bi bi-shield-check display-1 text-primary mb-4"></i>
                                    <h3 class="text-primary mb-3">Acceso Restringido</h3>
                                    <p class="lead text-muted mb-4">Esta página requiere autenticación. Por favor, inicia sesión para continuar.</p>

                                    <div class="action-buttons">
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-3">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>
                                            Iniciar Sesión
                                        </a>
                                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                                            <i class="bi bi-person-plus me-2"></i>
                                            Registrarse
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            @auth
            <!-- Quick Stats Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Tu Actividad
                    </h6>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-item text-center p-3 rounded bg-light">
                            <h4 class="text-primary mb-1">{{ Auth::user()->loans()->count() }}</h4>
                            <small class="text-muted">Préstamos Realizados</small>
                        </div>
                        <div class="stat-item text-center p-3 rounded bg-light mt-3">
                            <h4 class="text-success mb-1">{{ Auth::user()->activeLoans()->count() }}</h4>
                            <small class="text-muted">Préstamos Activos</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>
                        Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('books.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-search me-2"></i>
                            Buscar Libros
                        </a>
                        <a href="{{ route('loans.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-clock-history me-2"></i>
                            Ver Mis Préstamos
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-person-gear me-2"></i>
                            Editar Perfil
                        </a>
                    </div>
                </div>
            </div>
            @else
            <!-- Guest Information Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Información
                    </h6>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted">Para acceder a todas las funcionalidades, necesitas estar registrado en el sistema.</p>

                    <div class="d-grid gap-2">
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>
                            Crear Cuenta
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Ya tengo cuenta
                        </a>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </div>

    <!-- Recent Books Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-book-half me-2"></i>
                        Libros Recientes
                    </h6>
                    <a href="{{ route('books.index') }}" class="btn btn-sm btn-primary">Ver Todos</a>
                </div>
                <div class="card-body">
                    @if($recentBooks->count() > 0)
                        <div class="row g-3">
                            @foreach($recentBooks->take(4) as $book)
                            <div class="col-md-3">
                                <div class="book-preview">
                                    <div class="book-cover-small">
                                        <img src="{{ $book->cover_image ?? 'https://via.placeholder.com/80x120/1e40af/ffffff?text=USMP' }}"
                                             alt="{{ $book->title }}" class="img-fluid rounded">
                                    </div>
                                    <div class="book-info mt-2">
                                        <h6 class="small fw-semibold mb-1">{{ Str::limit($book->title, 30) }}</h6>
                                        <p class="small text-muted mb-1">{{ Str::limit($book->author, 25) }}</p>
                                        <span class="badge bg-{{ $book->isAvailable() ? 'success' : 'secondary' }} badge-sm">
                                            {{ $book->isAvailable() ? 'Disponible' : 'No disponible' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-book display-4 text-muted mb-3"></i>
                            <p class="text-muted">No hay libros disponibles en este momento.</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary">
                                Explorar Catálogo
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.user-avatar-xl {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--usmp-primary), var(--usmp-accent));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 auto;
    box-shadow: 0 4px 20px rgba(30, 64, 175, 0.3);
}

.welcome-content {
    animation: fadeInUp 0.6s ease-out;
}

.book-preview {
    text-align: center;
    padding: 1rem;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.book-preview:hover {
    background-color: #f8fafc;
    transform: translateY(-2px);
}

.book-cover-small {
    width: 80px;
    height: 120px;
    margin: 0 auto;
    overflow: hidden;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.book-cover-small img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-item {
    transition: all 0.2s ease;
}

.stat-item:hover {
    background-color: #e2e8f0 !important;
}
</style>
@endsection
