@extends('layouts.app')

@section('page_title', 'Categorías')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Categorías</h2>
                    <p class="text-muted mb-0">Administra las categorías para clasificar los libros</p>
                </div>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Nueva Categoría
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-tags fs-2"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $categories->count() }}</h3>
                                    <small class="opacity-75">Total Categorías</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-book fs-2"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $categories->sum('books_count') ?? 0 }}</h3>
                                    <small class="opacity-75">Libros Categorizados</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('categories.index') }}" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    placeholder="Buscar categorías...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="sort">
                                <option value="name" {{ request('sort')==='name' ? 'selected' : '' }}>
                                    Ordenar por Nombre
                                </option>
                                <option value="created_at" {{ request('sort')==='created_at' ? 'selected' : '' }}>
                                    Más Recientes
                                </option>
                                <option value="books_count" {{ request('sort')==='books_count' ? 'selected' : '' }}>
                                    Más Libros
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bi bi-funnel me-1"></i>
                                    Filtrar
                                </button>
                                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-1"></i>
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Categories Grid -->
            @if($categories->count() > 0)
            <div class="row">
                @foreach($categories as $category)
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <div class="card border-0 shadow-sm h-100 category-card">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="category-icon">
                                    <i class="bi bi-tag fs-3 text-primary"></i>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('categories.show', $category) }}">
                                                <i class="bi bi-eye me-2"></i>Ver Detalles
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('categories.edit', $category) }}">
                                                <i class="bi bi-pencil me-2"></i>Editar
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i>Eliminar
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <h5 class="card-title mb-2">{{ $category->name }}</h5>

                            @if($category->description)
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($category->description, 100) }}
                            </p>
                            @endif

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-book me-1"></i>
                                        {{ $category->books_count ?? 0 }} libros
                                    </span>
                                    <small class="text-muted">
                                        {{ $category->created_at->format('d/m/Y') }}
                                    </small>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('categories.show', $category) }}"
                                        class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </a>
                                    <a href="{{ route('categories.edit', $category) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($categories->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
            @endif
            @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-tags text-muted" style="font-size: 4rem;"></i>
                </div>
                <h4 class="text-muted mb-2">No hay categorías</h4>
                <p class="text-muted mb-4">
                    @if(request('search'))
                    No se encontraron categorías que coincidan con tu búsqueda.
                    @else
                    Comienza creando tu primera categoría para organizar los libros.
                    @endif
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>
                        Crear Primera Categoría
                    </a>
                    @if(request('search'))
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Ver Todas
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .category-card {
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        z-index: 1;
    }

    .category-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        z-index: 10;
    }

    .category-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: rgba(var(--bs-primary-rgb), 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn {
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .input-group-text {
        border-right: 0;
    }

    .input-group .form-control {
        border-left: 0;
    }

    .input-group .form-control:focus {
        box-shadow: none;
        border-color: #ced4da;
    }

    .input-group:focus-within .input-group-text {
        border-color: var(--bs-primary);
        color: var(--bs-primary);
    }

    .input-group:focus-within .form-control {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
    }

    .dropdown-toggle::after {
        display: none;
    }

    .dropdown {
        position: relative;
        z-index: 1000;
    }

    .dropdown-menu {
        z-index: 1050 !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .dropdown.show {
        z-index: 1051;
    }

    .dropdown-menu.show {
        z-index: 1052 !important;
    }

    .dropdown-menu[data-bs-popper] {
        z-index: 1055 !important;
    }

    .card-body {
        position: relative;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('select[name="sort"]').addEventListener('change', function() {
        this.form.submit();
    });
});
</script>
@endpush
@endsection
