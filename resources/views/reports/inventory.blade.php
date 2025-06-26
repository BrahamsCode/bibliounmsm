@extends('layouts.app')

@section('title', 'Inventario de Libros')

@section('content')
<div class="container-fluid">
    <h1 class="h4 mb-3"><i class="fas fa-archive me-2"></i>Inventario de Libros</h1>

    <form class="row g-3 mb-4" method="GET">
        <div class="col-md-4">
            <label class="form-label">Categoría</label>
            <select name="category_id" class="form-select">
                <option value="">Todas</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Disponibilidad</label>
            <select name="availability" class="form-select">
                <option value="all" {{ $availability == 'all' ? 'selected' : '' }}>Todos</option>
                <option value="available" {{ $availability == 'available' ? 'selected' : '' }}>Disponibles</option>
                <option value="unavailable" {{ $availability == 'unavailable' ? 'selected' : '' }}>No disponibles</option>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary me-2" type="submit"><i class="fas fa-search me-1"></i>Filtrar</button>
            <a href="{{ route('reports.export', ['type' => 'inventory']) }}" class="btn btn-success">
                <i class="fas fa-file-csv me-1"></i>Exportar CSV
            </a>
        </div>
    </form>

    <div class="row mb-3">
        <div class="col">
            <div class="alert alert-info">
                <strong>Total Libros:</strong> {{ $stats['total_books'] }} |
                <strong>Disponibles:</strong> {{ $stats['available_books'] }} |
                <strong>No disponibles:</strong> {{ $stats['unavailable_books'] }} |
                <strong>Stock Total:</strong> {{ $stats['total_stock'] }} |
                <strong>Stock Disponible:</strong> {{ $stats['available_stock'] }}
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Categoría</th>
                    <th>Editorial</th>
                    <th>Año</th>
                    <th>Páginas</th>
                    <th>Idioma</th>
                    <th>Stock</th>
                    <th>Disponible</th>
                    <th>En Préstamo</th>
                    <th>Ubicación</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                <tr>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->category->name ?? '-' }}</td>
                    <td>{{ $book->publisher }}</td>
                    <td>{{ $book->publication_date ? $book->publication_date->format('Y') : '' }}</td>
                    <td>{{ $book->pages }}</td>
                    <td>{{ $book->language }}</td>
                    <td>{{ $book->stock_quantity }}</td>
                    <td>{{ $book->available_quantity }}</td>
                    <td>{{ $book->stock_quantity - $book->available_quantity }}</td>
                    <td>{{ $book->location }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center">No hay libros para mostrar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
