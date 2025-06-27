@extends('layouts.app')

@section('page_title', 'Catálogo de Libros')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 text-primary mb-1">
                <i class="bi bi-book me-2"></i>
                Catálogo de Libros
            </h2>
            <p class="text-muted mb-0">Explora nuestra colección de {{ $books->total() }} libros disponibles</p>
        </div>

        @auth
        @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
        <div>
            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>
                Agregar Libro
            </a>
        </div>
        @endif
        @endauth
    </div>

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('books.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Título, autor o ISBN...">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="category" class="form-label">Categoría</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category')==$category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="available" class="form-label">Disponibilidad</label>
                    <select class="form-select" id="available" name="available">
                        <option value="">Todos</option>
                        <option value="1" {{ request('available')=='1' ? 'selected' : '' }}>
                            Solo disponibles
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>
                            Filtrar
                        </button>
                    </div>
                </div>
            </form>

            @if(request()->hasAny(['search', 'category', 'available']))
            <div class="mt-3">
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-circle me-1"></i>
                    Limpiar filtros
                </a>
                <small class="text-muted ms-2">
                    <i class="bi bi-info-circle me-1"></i>
                    Filtros activos:
                    @if(request('search'))
                    <span class="badge bg-primary">Búsqueda: "{{ request('search') }}"</span>
                    @endif
                    @if(request('category'))
                    <span class="badge bg-success">Categoría</span>
                    @endif
                    @if(request('available'))
                    <span class="badge bg-info">Solo disponibles</span>
                    @endif
                </small>
            </div>
            @endif
        </div>
    </div>

    <!-- Results Info -->
    @if($books->count() > 0)
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-muted">
            Mostrando {{ $books->firstItem() }} - {{ $books->lastItem() }} de {{ $books->total() }} resultados
        </div>
    </div>
    @endif

    <!-- Books Grid -->
    @if($books->count() > 0)
    <div class="row g-4">
        @foreach($books as $book)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card book-card h-100">
                <div class="card-img-top book-cover-container">
                    <img src="{{ $book->cover_image ?? 'https://via.placeholder.com/200x300/1e40af/ffffff?text=' . urlencode(substr($book->title, 0, 3)) }}"
                        alt="{{ $book->title }}" class="book-cover-img">

                    <!-- Availability Badge -->
                    <div class="availability-badge">
                        @if($book->isAvailable())
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ $book->available_quantity }} disponible(s)
                        </span>
                        @else
                        <span class="badge bg-secondary">
                            <i class="bi bi-x-circle me-1"></i>
                            No disponible
                        </span>
                        @endif
                    </div>
                </div>

                <div class="card-body d-flex flex-column">
                    <div class="mb-auto">
                        <h6 class="card-title text-primary mb-2">
                            {{ Str::limit($book->title, 50) }}
                        </h6>
                        <p class="card-text text-muted small mb-2">
                            <i class="bi bi-person me-1"></i>
                            {{ Str::limit($book->author, 30) }}
                        </p>
                        <p class="card-text small mb-2">
                            <i class="bi bi-tag me-1"></i>
                            <span class="text-primary">{{ $book->category->name }}</span>
                        </p>
                        @if($book->description)
                        <p class="card-text small text-muted">
                            {{ Str::limit($book->description, 80) }}
                        </p>
                        @endif
                    </div>

                    <div class="mt-3">
                        <div class="d-grid gap-2">
                            <!-- Ver Detalles (para todos) -->
                            <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>
                                Ver Detalles
                            </a>

                            @auth
                            @if(auth()->user()->isStudent())
                            @if($book->isAvailable())
                            @php
                            $userHasActiveLoan = $book->activeLoans()
                            ->where('user_id', auth()->id())
                            ->exists();
                            @endphp

                            @if(!$userHasActiveLoan)
                            <form action="{{ route('books.request-loan', $book) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-bookmark-plus me-1"></i>
                                    Solicitar Préstamo
                                </button>
                            </form>
                            @else
                            <button class="btn btn-info btn-sm" disabled>
                                <i class="bi bi-check-circle me-1"></i>
                                Ya prestado
                            </button>
                            @endif
                            @else
                            <button class="btn btn-secondary btn-sm" disabled>
                                <i class="bi bi-x-circle me-1"></i>
                                No disponible
                            </button>
                            @endif
                            @endif

                            @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
                            <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil me-1"></i>
                                Editar
                            </a>
                            @endif
                            @else
                            <a href="{{ route('login') }}" class="btn btn-info btn-sm">
                                <i class="bi bi-person-check me-1"></i>
                                Iniciar sesión
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginación Simple -->
    @if($books->hasPages())
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Navegación de libros">
            <ul class="pagination">
                {{-- Botón Anterior --}}
                @if ($books->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                @else
                <li class="page-item"><a class="page-link"
                        href="{{ $books->appends(request()->query())->previousPageUrl() }}">Anterior</a></li>
                @endif

                {{-- Números de página --}}
                @foreach(range(1, $books->lastPage()) as $page)
                @if($page == $books->currentPage())
                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                <li class="page-item"><a class="page-link"
                        href="{{ $books->appends(request()->query())->url($page) }}">{{ $page }}</a></li>
                @endif
                @endforeach

                {{-- Botón Siguiente --}}
                @if ($books->hasMorePages())
                <li class="page-item"><a class="page-link"
                        href="{{ $books->appends(request()->query())->nextPageUrl() }}">Siguiente</a></li>
                @else
                <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
                @endif
            </ul>
        </nav>
    </div>
    @endif
    @else
    <!-- Empty State -->
    <div class="text-center py-5">
        <div class="empty-state">
            <i class="bi bi-search display-1 text-muted mb-3"></i>
            <h4 class="text-muted">No se encontraron libros</h4>

            @if(request()->hasAny(['search', 'category', 'available']))
            <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
            <a href="{{ route('books.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left me-2"></i>
                Ver todos los libros
            </a>
            @else
            <p class="text-muted">Aún no hay libros en el catálogo</p>
            @auth
            @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>
                Agregar primer libro
            </a>
            @endif
            @endauth
            @endif
        </div>
    </div>
    @endif
</div>

<style>
    .book-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .book-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .book-cover-container {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    }

    .book-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .book-card:hover .book-cover-img {
        transform: scale(1.05);
    }

    .availability-badge {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        z-index: 10;
    }

    .card-title {
        font-weight: 600;
        line-height: 1.3;
    }

    .empty-state {
        padding: 3rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Paginación Custom */
    .pagination {
        --bs-pagination-color: #1e40af;
        --bs-pagination-bg: #fff;
        --bs-pagination-border-color: #dee2e6;
        --bs-pagination-hover-color: #fff;
        --bs-pagination-hover-bg: #1e40af;
        --bs-pagination-hover-border-color: #1e40af;
        --bs-pagination-active-color: #fff;
        --bs-pagination-active-bg: #1e40af;
        --bs-pagination-active-border-color: #1e40af;
        --bs-pagination-disabled-color: #6c757d;
        --bs-pagination-disabled-bg: #fff;
        --bs-pagination-disabled-border-color: #dee2e6;
    }

    .page-link {
        padding: 0.5rem 0.75rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .book-cover-container {
            height: 180px;
        }

        .card-title {
            font-size: 0.95rem;
        }

        .btn-sm {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }

        .pagination {
            font-size: 0.875rem;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding: 0.5rem;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Confirmación para solicitud de préstamo
    const loanForms = document.querySelectorAll('form[action*="request-loan"]');
    loanForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const bookTitle = this.closest('.book-card').querySelector('.card-title').textContent.trim();
            if (!confirm(`¿Estás seguro de que quieres solicitar el libro "${bookTitle}" en préstamo?`)) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
@endsection
