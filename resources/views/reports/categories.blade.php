@extends('layouts.app')

@section('title', 'Categorías Más Prestadas')

@section('content')
<div class="container-fluid">
    <h1 class="h4 mb-3"><i class="fas fa-tags me-2"></i>Categorías Más Prestadas</h1>

    <form class="row g-3 mb-4" method="GET">
        <div class="col-md-3">
            <label class="form-label">Desde</label>
            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Hasta</label>
            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
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
                    <th>Categoría</th>
                    <th>Libros</th>
                    <th>Préstamos</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $i => $cat)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->books->count() }}</td>
                    <td><span class="badge bg-warning">{{ $cat->loans_count }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No hay datos para mostrar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
