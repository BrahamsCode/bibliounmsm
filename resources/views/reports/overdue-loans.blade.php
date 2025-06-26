@extends('layouts.app')

@section('title', 'Préstamos Vencidos')

@section('content')
<div class="container-fluid">
    <h1 class="h4 mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Préstamos Vencidos</h1>

    <div class="row mb-3">
        <div class="col">
            <div class="alert alert-warning">
                <strong>1-7 días:</strong> {{ $overdueStats['1-7_days'] }} |
                <strong>8-15 días:</strong> {{ $overdueStats['8-15_days'] }} |
                <strong>16-30 días:</strong> {{ $overdueStats['16-30_days'] }} |
                <strong>+30 días:</strong> {{ $overdueStats['more_30_days'] }}
            </div>
        </div>
        <div class="col-auto">
            <a href="{{ route('reports.export', ['type' => 'overdue']) }}" class="btn btn-danger">
                <i class="fas fa-file-csv me-1"></i>Exportar CSV
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Código</th>
                    <th>Estudiante</th>
                    <th>Email</th>
                    <th>Libro</th>
                    <th>Categoría</th>
                    <th>Fecha Préstamo</th>
                    <th>Vencimiento</th>
                    <th>Días de Retraso</th>
                </tr>
            </thead>
            <tbody>
                @forelse($overdueLoans as $loan)
                <tr>
                    <td>{{ $loan->loan_code }}</td>
                    <td>{{ $loan->user->name ?? '-' }}</td>
                    <td>{{ $loan->user->email ?? '-' }}</td>
                    <td>{{ $loan->book->title ?? '-' }}</td>
                    <td>{{ $loan->book->category->name ?? '-' }}</td>
                    <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                    <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                    <td class="text-danger fw-bold">{{ $loan->due_date->diffInDays(now()) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No hay préstamos vencidos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
