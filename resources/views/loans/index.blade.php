@extends('layouts.app')

@section('page_title', 'Gestión de Préstamos')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 text-primary mb-1">
                <i class="bi bi-arrow-left-right me-2"></i>
                @auth
                @if(auth()->user()->isStudent())
                Mis Préstamos
                @else
                Gestión de Préstamos
                @endif
                @else
                Préstamos
                @endauth
            </h2>
            <p class="text-muted mb-0">
                @auth
                @if(auth()->user()->isStudent())
                Gestiona tus libros prestados
                @else
                Total de {{ $loans->total() }} préstamos en el sistema
                @endif
                @endauth
            </p>
        </div>

        @auth
        @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
        <div>
            <a href="{{ route('loans.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>
                Nuevo Préstamo
            </a>
        </div>
        @endif
        @endauth
    </div>

    <!-- Quick Stats Cards (for admins/librarians) -->
    @auth
    @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Préstamos Activos</h6>
                            <h4 class="mb-0">{{ $loans->where('status', 'active')->count() }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-clock-history fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Devueltos Hoy</h6>
                            <h4 class="mb-0">{{ $loans->where('status', 'returned')->where('return_date',
                                today())->count() }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Vencidos</h6>
                            <h4 class="mb-0">{{ $loans->where('status', 'active')->filter(fn($loan) =>
                                $loan->isOverdue())->count() }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-triangle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total del Mes</h6>
                            <h4 class="mb-0">{{ $loans->whereBetween('loan_date', [now()->startOfMonth(),
                                now()->endOfMonth()])->count() }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-graph-up fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endauth

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('loans.index') }}" class="row g-3">
                @auth
                @if(!auth()->user()->isStudent())
                <div class="col-md-4">
                    <label for="search" class="form-label">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Estudiante, código o libro...">
                    </div>
                </div>
                @endif
                @endauth

                <div class="col-md-3">
                    <label for="status" class="form-label">Estado</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>
                            Activos
                        </option>
                        <option value="returned" {{ request('status')=='returned' ? 'selected' : '' }}>
                            Devueltos
                        </option>
                        <option value="overdue" {{ request('status')=='overdue' ? 'selected' : '' }}>
                            Vencidos
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>
                            Filtrar
                        </button>
                    </div>
                </div>

                @if(request()->hasAny(['search', 'status']))
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>
                            Limpiar
                        </a>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Loans List -->
    @if($loans->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            @auth
                            @if(!auth()->user()->isStudent())
                            <th>Estudiante</th>
                            @endif
                            @endauth
                            <th>Libro</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Vencimiento</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                        <tr class="{{ $loan->isOverdue() && $loan->status === 'active' ? 'table-warning' : '' }}">
                            <td>
                                <strong class="text-primary">{{ $loan->loan_code }}</strong>
                            </td>

                            @auth
                            @if(!auth()->user()->isStudent())
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">
                                        {{ substr($loan->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $loan->user->name }}</strong><br>
                                        <small class="text-muted">{{ $loan->user->student_code }}</small>
                                    </div>
                                </div>
                            </td>
                            @endif
                            @endauth

                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $loan->book->cover_image ?? 'https://via.placeholder.com/40x60/1e40af/ffffff?text=' . urlencode(substr($loan->book->title, 0, 2)) }}"
                                        alt="{{ $loan->book->title }}" class="book-thumbnail me-2">
                                    <div>
                                        <strong>{{ Str::limit($loan->book->title, 40) }}</strong><br>
                                        <small class="text-muted">{{ $loan->book->author }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="fw-semibold">{{ $loan->loan_date->format('d/m/Y') }}</span><br>
                                <small class="text-muted">{{ $loan->loan_date->diffForHumans() }}</small>
                            </td>

                            <td>
                                <span
                                    class="fw-semibold {{ $loan->isOverdue() && $loan->status === 'active' ? 'text-danger' : '' }}">
                                    {{ $loan->due_date->format('d/m/Y') }}
                                </span><br>
                                <small class="text-muted">
                                    @if($loan->status === 'active')
                                    @if($loan->isOverdue())
                                    <span class="text-danger">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Vencido {{ $loan->due_date->diffForHumans() }}
                                    </span>
                                    @else
                                    <span class="text-success">
                                        Vence {{ $loan->due_date->diffForHumans() }}
                                    </span>
                                    @endif
                                    @elseif($loan->return_date)
                                    Devuelto {{ $loan->return_date->format('d/m/Y') }}
                                    @endif
                                </small>
                            </td>

                            <td>
                                @if($loan->status === 'active')
                                @if($loan->isOverdue())
                                <span class="badge bg-danger">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Vencido
                                </span>
                                @else
                                <span class="badge bg-success">
                                    <i class="bi bi-clock me-1"></i>
                                    Activo
                                </span>
                                @endif
                                @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Devuelto
                                </span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('loans.show', $loan) }}" class="btn btn-outline-primary"
                                        title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @auth
                                    @if((auth()->user()->isAdmin() || auth()->user()->isLibrarian()) && $loan->status
                                    === 'active')
                                    <form action="{{ route('loans.return', $loan) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('¿Confirmar la devolución de este libro?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success"
                                            title="Marcar como devuelto">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @endauth
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $loans->appends(request()->query())->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="text-center py-5">
        <div class="empty-state">
            <i class="bi bi-inbox display-1 text-muted mb-3"></i>

            @auth
            @if(auth()->user()->isStudent())
            <h4 class="text-muted">No tienes préstamos</h4>
            <p class="text-muted">Explora nuestro catálogo y solicita tu primer libro</p>
            <a href="{{ route('books.index') }}" class="btn btn-primary">
                <i class="bi bi-book me-2"></i>
                Ver Catálogo
            </a>
            @else
            <h4 class="text-muted">No se encontraron préstamos</h4>
            @if(request()->hasAny(['search', 'status']))
            <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
            <a href="{{ route('loans.index') }}" class="btn btn-primary">
                Ver todos los préstamos
            </a>
            @else
            <p class="text-muted">Aún no hay préstamos registrados en el sistema</p>
            <a href="{{ route('loans.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>
                Crear primer préstamo
            </a>
            @endif
            @endif
            @endauth
        </div>
    </div>
    @endif
</div>

<style>
    .user-avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--UNMSM-primary), var(--UNMSM-accent));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .book-thumbnail {
        width: 40px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }

    .empty-state {
        padding: 3rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
    }

    .btn-group-sm>.btn {
        padding: 0.25rem 0.5rem;
    }

    /* Stats cards hover effect */
    .card:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }

    /* Table enhancements */
    .table th {
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        color: #495057;
    }

    .table td {
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(30, 64, 175, 0.05);
    }

    /* Badge enhancements */
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }

        .btn-group-sm>.btn {
            padding: 0.2rem 0.4rem;
        }

        .book-thumbnail {
            width: 30px;
            height: 45px;
        }

        .user-avatar-sm {
            width: 24px;
            height: 24px;
            font-size: 0.65rem;
        }
    }
</style>
@endsection