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
            </div>
            @endif
        </div>
    </div>

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
                            <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>
                                Ver Detalles
                            </a>

                            @auth
                            @if(auth()->user()->isStudent() && $book->isAvailable())
                            @php
                            $userHasActiveLoan = $book->activeLoans()
                            ->where('user_id', auth()->id())
                            ->exists();
                            @endphp

                            @if(!$userHasActiveLoan)
                            <form action="{{ route('books.request-loan', $book) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de que quieres solicitar este libro en préstamo?')">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-bookmark-plus me-1"></i>
                                    Solicitar Préstamo
                                </button>
                            </form>
                            @else
                            <button class="btn btn-info btn-sm" disabled>
                                <i class="bi bi-check-circle me-1"></i>
                                Ya lo tienes prestado
                            </button>
                            @endif
                            @elseif(auth()->user()->isStudent() && !$book->isAvailable())
                            <button class="btn btn-secondary btn-sm" disabled>
                                <i class="bi bi-x-circle me-1"></i>
                                No disponible
                            </button>
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
                                Iniciar sesión para préstamo
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $books->appends(request()->query())->links() }}
    </div>
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

    /* Filter section styling */
    .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
    }

    .form-control,
    .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--UNMSM-primary);
        box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.15);
    }

    .input-group-text {
        background-color: #f9fafb;
        border: 2px solid #e5e7eb;
        border-right: none;
        color: #6b7280;
    }

    /* Book actions styling */
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .btn-outline-primary:hover {
        transform: translateY(-1px);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
    }

    .btn-info {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border: none;
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border: none;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .book-cover-container {
            height: 180px;
        }

        .card-title {
            font-size: 0.95rem;
        }

        .col-md-4,
        .col-md-3,
        .col-md-2 {
            margin-bottom: 1rem;
        }

        .btn-sm {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
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

        .book-card {
            margin-bottom: 1rem;
        }
    }

    /* Loading animation for buttons */
    .btn[type="submit"]:disabled {
        position: relative;
        color: transparent !important;
    }

    .btn[type="submit"]:disabled::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Enhanced hover effects */
    .book-card:hover .card-title {
        color: var(--UNMSM-primary) !important;
    }

    .book-card:hover .availability-badge .badge {
        transform: scale(1.05);
    }

    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        color: var(--UNMSM-primary);
        border: 1px solid #e5e7eb;
        padding: 0.5rem 0.75rem;
    }

    .page-link:hover {
        color: white;
        background-color: var(--UNMSM-primary);
        border-color: var(--UNMSM-primary);
    }

    .page-item.active .page-link {
        background-color: var(--UNMSM-primary);
        border-color: var(--UNMSM-primary);
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter change (optional)
    const filterSelects = document.querySelectorAll('#category, #available');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Uncomment next line if you want auto-submit on filter change
            // this.closest('form').submit();
        });
    });

    // Enhanced search functionality
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            // Add loading indicator or debounced search here if needed
        });
    }

    // Confirm loan requests
    const loanForms = document.querySelectorAll('form[action*="request-loan"]');
    loanForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const bookTitle = this.closest('.book-card').querySelector('.card-title').textContent.trim();
            if (!confirm(`¿Estás seguro de que quieres solicitar el libro "${bookTitle}" en préstamo?`)) {
                e.preventDefault();
            }
        });
    });

    // Loading state for loan buttons
    const loanButtons = document.querySelectorAll('button[type="submit"]');
    loanButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.disabled = true;
            setTimeout(() => {
                this.disabled = false;
            }, 3000); // Re-enable after 3 seconds as fallback
        });
    });
});
</script>
@endpush
@endsection