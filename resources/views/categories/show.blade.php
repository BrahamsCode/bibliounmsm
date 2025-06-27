@extends('layouts.app')

@section('page_title', $category->name . ' - Detalles')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item">
                                <a href="{{ route('categories.index') }}" class="text-decoration-none">
                                    Categorías
                                </a>
                            </li>
                            <li class="breadcrumb-item active">{{ $category->name }}</li>
                        </ol>
                    </nav>
                    <h2 class="mb-1">{{ $category->name }}</h2>
                    <p class="text-muted mb-0">
                        Detalles y libros asociados a esta categoría
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>
                        Editar
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            Acciones
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('categories.edit', $category) }}">
                                    <i class="bi bi-pencil me-2"></i>Editar Categoría
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('¿Estás seguro de eliminar esta categoría? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-trash me-2"></i>Eliminar Categoría
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Volver
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Category Details -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 pb-0">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2 text-primary"></i>
                                Información de la Categoría
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="category-icon-large mx-auto mb-3">
                                    <i class="bi bi-tag text-primary"></i>
                                </div>
                                <h4 class="mb-1">{{ $category->name }}</h4>
                                @if($category->description)
                                <p class="text-muted">{{ $category->description }}</p>
                                @endif
                            </div>

                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h3 class="text-primary mb-1">{{ $category->books_count ?? 0 }}</h3>
                                        <small class="text-muted">Libros</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h3 class="text-success mb-1">
                                        {{ $category->created_at->diffForHumans() }}
                                    </h3>
                                    <small class="text-muted">Creada</small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">
                                            <i class="bi bi-calendar-plus me-1"></i>
                                            Fecha de Creación:
                                        </span>
                                        <span class="fw-semibold">
                                            {{ $category->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">
                                            <i class="bi bi-arrow-repeat me-1"></i>
                                            Última Actualización:
                                        </span>
                                        <span class="fw-semibold">
                                            {{ $category->updated_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header bg-white border-0 pb-0">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-lightning me-2 text-warning"></i>
                                Acciones Rápidas
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('books.index', ['category' => $category->id]) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-book me-1"></i>
                                    Ver Todos los Libros
                                </a>
                                <a href="{{ route('books.create', ['category' => $category->id]) }}"
                                    class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Agregar Libro a Categoría
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Books in Category -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-book me-2 text-primary"></i>
                                    Libros en esta Categoría
                                    <span class="badge bg-primary ms-2">{{ $books->count() }}</span>
                                </h5>
                                @if($books->count() > 0)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="bi bi-funnel me-1"></i>
                                        Ordenar
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="?sort=title">Por Título</a></li>
                                        <li><a class="dropdown-item" href="?sort=author">Por Autor</a></li>
                                        <li><a class="dropdown-item" href="?sort=created_at">Más Recientes</a></li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($books->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">Libro</th>
                                            <th class="border-0">Autor</th>
                                            <th class="border-0">Estado</th>
                                            <th class="border-0">Fecha</th>
                                            <th class="border-0 text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($books as $book)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="book-cover-small me-3">
                                                        @if($book->cover_image)
                                                        <img src="{{ Storage::url($book->cover_image) }}"
                                                            alt="{{ $book->title }}" class="rounded">
                                                        @else
                                                        <div
                                                            class="placeholder-cover rounded d-flex align-items-center justify-content-center">
                                                            <i class="bi bi-book text-muted"></i>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $book->title }}</h6>
                                                        @if($book->subtitle)
                                                        <small class="text-muted">{{ $book->subtitle }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-medium">{{ $book->author }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $book->status === 'available' ? 'success' : ($book->status === 'borrowed' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($book->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $book->created_at->format('d/m/Y') }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('books.show', $book) }}"
                                                        class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('books.edit', $book) }}"
                                                        class="btn btn-outline-secondary btn-sm">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination for books if needed -->
                            @if($books->hasPages())
                            <div class="card-footer bg-white border-0">
                                {{ $books->links() }}
                            </div>
                            @endif
                            @else
                            <!-- Empty State for Books -->
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="text-muted mb-2">No hay libros en esta categoría</h5>
                                <p class="text-muted mb-3">
                                    Comienza agregando libros a la categoría "{{ $category->name }}"
                                </p>
                                <a href="{{ route('books.create', ['category' => $category->id]) }}"
                                    class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Agregar Primer Libro
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .category-icon-large {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: rgba(var(--bs-primary-rgb), 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .category-icon-large i {
        font-size: 2.5rem;
    }

    .book-cover-small {
        width: 40px;
        height: 50px;
    }

    .book-cover-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .placeholder-cover {
        width: 40px;
        height: 50px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    .card {
        transition: all 0.2s ease-in-out;
    }

    .btn {
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.05);
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: "›";
        font-weight: bold;
    }

    .border-end {
        border-right: 1px solid #dee2e6 !important;
    }

    @media (max-width: 768px) {
        .border-end {
            border-right: none !important;
            border-bottom: 1px solid #dee2e6 !important;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Tooltip initialization if needed
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
@endsection
