@extends('layouts.app')

@section('page_title', 'Préstamo ' . $loan->loan_code)

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('loans.index') }}" class="text-decoration-none">
                    <i class="bi bi-arrow-left-right me-1"></i>
                    Préstamos
                </a>
            </li>
            <li class="breadcrumb-item active">{{ $loan->loan_code }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Loan Details Card -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Detalles del Préstamo
                    </h5>
                    <div>
                        @if($loan->status === 'active')
                            @if($loan->isOverdue())
                                <span class="badge bg-danger fs-6">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Vencido
                                </span>
                            @else
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-clock me-1"></i>
                                    Activo
                                </span>
                            @endif
                        @else
                            <span class="badge bg-secondary fs-6">
                                <i class="bi bi-check-circle me-1"></i>
                                Devuelto
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <!-- Loan Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold text-muted">Código de Préstamo:</td>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $loan->loan_code }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Fecha de Préstamo:</td>
                                    <td>
                                        {{ $loan->loan_date->format('d/m/Y') }}
                                        <small class="text-muted">({{ $loan->loan_date->diffForHumans() }})</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Fecha de Vencimiento:</td>
                                    <td>
                                        <span class="{{ $loan->isOverdue() && $loan->status === 'active' ? 'text-danger fw-bold' : '' }}">
                                            {{ $loan->due_date->format('d/m/Y') }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            @if($loan->status === 'active')
                                                @if($loan->isOverdue())
                                                    <span class="text-danger">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                                        Vencido {{ $loan->due_date->diffForHumans() }}
                                                    </span>
                                                @else
                                                    Vence {{ $loan->due_date->diffForHumans() }}
                                                @endif
                                            @endif
                                        </small>
                                    </td>
                                </tr>
                                @if($loan->return_date)
                                <tr>
                                    <td class="fw-semibold text-muted">Fecha de Devolución:</td>
                                    <td>
                                        {{ $loan->return_date->format('d/m/Y H:i') }}
                                        <small class="text-muted">({{ $loan->return_date->diffForHumans() }})</small>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold text-muted">Estado:</td>
                                    <td>
                                        @if($loan->status === 'active')
                                            @if($loan->isOverdue())
                                                <span class="badge bg-danger">Vencido</span>
                                            @else
                                                <span class="badge bg-success">Activo</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Devuelto</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Duración:</td>
                                    <td>
                                        @if($loan->return_date)
                                            {{ $loan->loan_date->diffInDays($loan->return_date) }} días
                                        @else
                                            {{ $loan->loan_date->diffInDays(now()) }} días (en curso)
                                        @endif
                                    </td>
                                </tr>
                                @if($loan->isOverdue() && $loan->status === 'active')
                                <tr>
                                    <td class="fw-semibold text-muted">Días de Retraso:</td>
                                    <td>
                                        <span class="text-danger fw-bold">
                                            {{ $loan->due_date->diffInDays(now()) }} días
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Multa Estimada:</td>
                                    <td>
                                        <span class="text-danger fw-bold">
                                            S/ {{ number_format($loan->due_date->diffInDays(now()) * 1.00, 2) }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Student Information -->
                    <div class="mb-4">
                        <h6 class="fw-semibold border-bottom pb-2 mb-3">
                            <i class="bi bi-person me-2"></i>
                            Información del Estudiante
                        </h6>
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="user-avatar-large">
                                    {{ substr($loan->user->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="col-md-9">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-semibold text-muted">Nombre:</td>
                                        <td>{{ $loan->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Código de Estudiante:</td>
                                        <td><code>{{ $loan->user->student_code }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Email:</td>
                                        <td>
                                            <a href="mailto:{{ $loan->user->email }}" class="text-decoration-none">
                                                {{ $loan->user->email }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Préstamos Activos:</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $loan->user->activeLoans()->count() }} activos
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Book Information -->
                    <div class="mb-4">
                        <h6 class="fw-semibold border-bottom pb-2 mb-3">
                            <i class="bi bi-book me-2"></i>
                            Información del Libro
                        </h6>
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <img src="{{ $loan->book->cover_image ?? 'https://via.placeholder.com/150x200/1e40af/ffffff?text=' . urlencode(substr($loan->book->title, 0, 3)) }}"
                                     alt="{{ $loan->book->title }}"
                                     class="img-fluid rounded book-cover-detail">
                            </div>
                            <div class="col-md-9">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-semibold text-muted">Título:</td>
                                        <td>
                                            <a href="{{ route('books.show', $loan->book) }}"
                                               class="text-decoration-none fw-semibold">
                                                {{ $loan->book->title }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Autor:</td>
                                        <td>{{ $loan->book->author }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">ISBN:</td>
                                        <td><code>{{ $loan->book->isbn }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Categoría:</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $loan->book->category->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Ubicación:</td>
                                        <td>{{ $loan->book->location ?? 'No especificada' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($loan->notes)
                    <div class="mb-4">
                        <h6 class="fw-semibold border-bottom pb-2 mb-3">
                            <i class="bi bi-sticky me-2"></i>
                            Notas
                        </h6>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $loan->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-gear me-2"></i>
                        Acciones
                    </h6>
                </div>
                <div class="card-body">
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
                            @if($loan->status === 'active')
                                <!-- Return Book -->
                                <form action="{{ route('loans.return', $loan) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Confirmar la devolución de este libro?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 mb-2">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Marcar como Devuelto
                                    </button>
                                </form>

                                <!-- Extend Loan -->
                                <button type="button"
                                        class="btn btn-warning w-100 mb-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#extendLoanModal">
                                    <i class="bi bi-calendar-plus me-2"></i>
                                    Extender Préstamo
                                </button>
                            @endif

                            <!-- Edit Loan -->
                            <a href="{{ route('loans.index') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="bi bi-arrow-left me-2"></i>
                                Volver a Préstamos
                            </a>
                        @endif

                        @if(auth()->user()->isStudent())
                            <div class="alert alert-info">
                                <h6 class="fw-semibold">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Información Importante
                                </h6>
                                <ul class="small mb-0">
                                    <li>Cuida el libro durante el período de préstamo</li>
                                    <li>Devuelve antes de la fecha de vencimiento</li>
                                    <li>Contacta a la biblioteca para renovaciones</li>
                                    @if($loan->isOverdue() && $loan->status === 'active')
                                        <li class="text-danger">
                                            <strong>Tu préstamo está vencido. Devuelve el libro lo antes posible.</strong>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                            <a href="{{ route('loans.index') }}" class="btn btn-primary w-100">
                                <i class="bi bi-arrow-left me-2"></i>
                                Volver a Mis Préstamos
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>
                        Estadísticas Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $loan->user->loans()->count() }}</h4>
                                <small class="text-muted">Total préstamos</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-success mb-0">{{ $loan->user->activeLoans()->count() }}</h4>
                            <small class="text-muted">Activos</small>
                        </div>
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-info mb-0">{{ $loan->book->activeLoans()->count() }}</h4>
                                <small class="text-muted">Copias prestadas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning mb-0">{{ $loan->book->available_quantity }}</h4>
                            <small class="text-muted">Disponibles</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Extend Loan Modal -->
@auth
    @if((auth()->user()->isAdmin() || auth()->user()->isLibrarian()) && $loan->status === 'active')
    <div class="modal fade" id="extendLoanModal" tabindex="-1" aria-labelledby="extendLoanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('loans.extend', $loan) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="extendLoanModalLabel">
                            <i class="bi bi-calendar-plus me-2"></i>
                            Extender Préstamo
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="loan-summary mb-3">
                            <h6 class="fw-semibold">Préstamo a extender:</h6>
                            <p class="text-primary mb-1">{{ $loan->book->title }}</p>
                            <p class="text-muted small">Estudiante: {{ $loan->user->name }}</p>
                            <p class="text-muted small">Vencimiento actual: {{ $loan->due_date->format('d/m/Y') }}</p>
                        </div>

                        <div class="mb-3">
                            <label for="new_due_date" class="form-label">Nueva Fecha de Vencimiento</label>
                            <input type="date"
                                   class="form-control"
                                   id="new_due_date"
                                   name="new_due_date"
                                   min="{{ $loan->due_date->addDay()->format('Y-m-d') }}"
                                   value="{{ $loan->due_date->addDays(14)->format('Y-m-d') }}"
                                   required>
                            <div class="form-text">
                                La nueva fecha debe ser posterior al vencimiento actual
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="extension_reason" class="form-label">Motivo de la extensión</label>
                            <textarea class="form-control"
                                      id="extension_reason"
                                      name="extension_reason"
                                      rows="3"
                                      placeholder="Razón para extender el préstamo..."></textarea>
                        </div>

                        <div class="alert alert-warning">
                            <small>
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <strong>Nota:</strong> Solo se permite una extensión por préstamo.
                                Verifica que no haya otros estudiantes esperando este libro.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle me-2"></i>
                            Extender Préstamo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endauth

<style>
.user-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--usmp-primary), var(--usmp-accent));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    font-weight: 700;
    margin: 0 auto;
    box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
}

.book-cover-detail {
    max-width: 150px;
    max-height: 200px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-radius: 12px;
}

.table-borderless td {
    padding: 0.5rem 0;
    border: none;
}

.loan-summary {
    border-left: 4px solid var(--usmp-primary);
    padding-left: 1rem;
    background-color: #f8fafc;
    padding: 1rem;
    border-radius: 0 8px 8px 0;
}

/* Status badge enhancements */
.badge {
    font-weight: 500;
}

.badge.fs-6 {
    font-size: 0.9rem !important;
    padding: 0.5rem 0.75rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .user-avatar-large {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .book-cover-detail {
        max-width: 100px;
        max-height: 150px;
    }

    .table-borderless {
        font-size: 0.9rem;
    }
}

/* Animation for overdue items */
@keyframes pulse-danger {
    0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}

.text-danger.fw-bold {
    animation: pulse-danger 2s infinite;
}

/* Button hover effects */
.btn:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
}

/* Modal enhancements */
.modal-content {
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

.modal-header {
    border-bottom: 2px solid #f1f5f9;
}

.modal-footer {
    border-top: 2px solid #f1f5f9;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate suggested extension date
    const dueDateInput = document.getElementById('new_due_date');
    if (dueDateInput) {
        dueDateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const currentDueDate = new Date('{{ $loan->due_date->format('Y-m-d') }}');
            const daysDiff = Math.ceil((selectedDate - currentDueDate) / (1000 * 60 * 60 * 24));

            if (daysDiff > 21) {
                alert('Advertencia: La extensión es mayor a 21 días. Considera un período más corto.');
            }
        });
    }

    // Confirmation for return action
    const returnForms = document.querySelectorAll('form[action*="return"]');
    returnForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const bookTitle = '{{ $loan->book->title }}';
            const studentName = '{{ $loan->user->name }}';

            if (!confirm(`¿Confirmar la devolución del libro "${bookTitle}" por parte de ${studentName}?`)) {
                e.preventDefault();
            }
        });
    });

    // Show overdue warning
    @if($loan->isOverdue() && $loan->status === 'active')
        const overdueWarning = document.createElement('div');
        overdueWarning.className = 'alert alert-danger position-fixed';
        overdueWarning.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
        overdueWarning.innerHTML = `
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            <strong><i class="bi bi-exclamation-triangle me-2"></i>Préstamo Vencido</strong><br>
            <small>Este préstamo está vencido desde hace {{ $loan->due_date->diffInDays(now()) }} días.</small>
        `;
        document.body.appendChild(overdueWarning);

        // Auto-remove after 10 seconds
        setTimeout(() => {
            if (overdueWarning.parentElement) {
                overdueWarning.remove();
            }
        }, 10000);
    @endif
});
</script>
@endpush
@endsection
