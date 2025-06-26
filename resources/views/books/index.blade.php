@extends('layouts.app')

@section('title', 'Catálogo de Libros - BiblioUNMSM')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-3">
            <!-- Filtros -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('catalog') }}">
                        <div class="mb-3">
                            <label for="search" class="form-label">Buscar</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Título, autor, ISBN...">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Categoría</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                        @if(request()->hasAny(['search', 'category']))
                        <a href="{{ route('catalog') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-times me-2"></i>Limpiar
                        </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Catálogo de Libros</h2>
                    <p class="text-muted mb-0">{{ $books->total() }} libros encontrados</p>
                </div>
                @auth
                    @if(Auth::user()->isLibrarian() || Auth::user()->isAdmin())
                    <a href="{{ route('books.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nuevo Libro
                    </a>
                    @endif
                @endauth
            </div>

            @if($books->count() > 0)
            <div class="row g-4">
                @foreach($books as $book)
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="{{ $book->cover_image ?? 'https://via.placeholder.com/300x400' }}"
                                 alt="{{ $book->title }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 p-2">
                                @if($book->isAvailable())
                                    <span class="badge bg-success">Disponible</span>
                                @else
                                    <span class="badge bg-danger">No disponible</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ Str::limit($book->title, 50) }}</h6>
                            <p class="card-text text-muted small mb-2">Por: {{ $book->author }}</p>
                            <p class="card-text text-muted small mb-2">Editorial: {{ $book->publisher ?? 'N/A' }}</p>
                            <div class="mb-2">
                                <span class="badge bg-outline-secondary">{{ $book->category->name }}</span>
                                @if($book->language !== 'español')
                                <span class="badge bg-outline-info">{{ ucfirst($book->language) }}</span>
                                @endif
                            </div>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $book->available_quantity }}/{{ $book->stock_quantity }} disponibles</small>
                                    <a href="{{ route('catalog.show', $book) }}" class="btn btn-outline-primary btn-sm">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $books->appends(request()->query())->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No se encontraron libros</h4>
                <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('catalog') }}" class="btn btn-primary">Ver todos los libros</a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
