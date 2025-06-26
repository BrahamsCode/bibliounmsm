@extends('layouts.app')

@section('title', 'Libros Más Prestados')

@section('content')
<div class="container-fluid">
    <h1 class="h4 mb-3"><i class="fas fa-star me-2"></i>Libros Más Prestados</h1>

    <form class="row g-3 mb-4" method="GET">
        <div class="col-md-3">
            <label class="form-label">Desde</label>
            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Hasta</label>
            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Cantidad</label>
            <input type="number" name="limit" class="form-control" value="{{ $limit }}" min="1" max="100">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search me-1"></i>Filtrar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Categoría</th>
                    <th>Préstamos</th>
                </tr>
            </thead>
            <tbody>
                @forelse($popularBooks as $i => $book)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->category->name ?? '-' }}</td>
                    <td><span class="badge bg-primary">{{ $book->loans_count }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No hay datos para mostrar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
