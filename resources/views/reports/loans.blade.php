@extends('layouts.app')

@section('title', 'Reporte de Préstamos')

@section('content')
<div class="container-fluid">
    <h1 class="h4 mb-3"><i class="fas fa-handshake me-2"></i>Reporte de Préstamos</h1>

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
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Todos</option>
                <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Activos</option>
                <option value="returned" {{ $status == 'returned' ? 'selected' : '' }}>Devueltos</option>
                <option value="overdue" {{ $status == 'overdue' ? 'selected' : '' }}>Vencidos</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary me-2" type="submit"><i class="fas fa-search me-1"></i>Filtrar</button>
            <a href="{{ route('reports.export', ['type' => 'loans', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success">
                <i class="fas fa-file-csv me-1"></i>Exportar CSV
            </a>
        </div>
    </form>

    <div class="row mb-3">
        <div class="col">
            <div class="alert alert-info">
                <strong>Total:</strong> {{ $stats['total_loans'] }} |
                <strong>Activos:</strong> {{ $stats['active_loans'] }} |
                <strong>Devueltos:</strong> {{ $stats['returned_loans'] }} |
                <strong>Vencidos:</strong> {{ $stats['overdue_loans'] }}
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Código</th>
                    <th>Estudiante</th>
                    <th>Libro</th>
                    <th>Categoría</th>
                    <th>Fecha Préstamo</th>
                    <th>Vencimiento</th>
                    <th>Devolución</th>
                    <th>Estado</th>
                    <th>Días de Retraso</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                <tr>
                    <td>{{ $loan->loan_code }}</td>
                    <td>{{ $loan->user->name ?? '-' }}</td>
                    <td>{{ $loan->book->title ?? '-' }}</td>
                    <td>{{ $loan->book->category->name ?? '-' }}</td>
                    <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                    <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                    <td>{{ $loan->return_date ? $loan->return_date->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($loan->status == 'active')
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Devuelto</span>
                        @endif
                    </td>
                    <td>
                        @if($loan->status == 'active' && $loan->due_date < now())
                            <span class="text-danger fw-bold">{{ $loan->due_date->diffInDays(now()) }}</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">No hay préstamos en el período seleccionado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
