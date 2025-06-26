@extends('layouts.app')

@section('page_title', $book->title)

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('books.index') }}" class="text-decoration-none">
                    <i class="bi bi-book me-1"></i>
                    Catálogo
                </a>
            </li>
            <li class="breadcrumb-item active">{{ Str::limit($book->title, 50) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Book Cover and Quick Actions -->
        <div class="col-lg-4">
            <div class="card book-detail-card">
                <div class="book-cover-large">
                    <img src="{{ $book->cover_image ?? 'https://via.placeholder.com/300x450/1e40af/ffffff?text=' . urlencode(substr($book->title, 0, 3)) }}"
                         alt="{{ $book->title }}"
                         class="img-fluid rounded">
                </div>

                <div class="card-body">
                    <!-- Availability Status -->
                    <div class="availability-section mb-3">
                        @if($book->isAvailable())
                            <div class="alert alert-success d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <div>
                                    <strong>Disponible</strong><br>
                                    <small>{{ $book->available_quantity }} de {{ $book->stock_quantity }} ejemplares</small>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div>
                                    <strong>No disponible</strong><br>
                                    <small>Todos los ejemplares están prestados</small>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        @auth
                            @if(auth()->user()->isStudent())
                                @if($book->isAvailable() && !$userHasActiveLoan)
                                    <button type="button"
                                            class="btn btn-success btn-lg"
                                            data-bs-toggle="modal"
                                            data-bs-target="#loanRequestModal">
                                        <i class="bi bi-bookmark-plus me-2"></i>
                                        Solicitar Préstamo
                                    </button>
                                @elseif($userHasActiveLoan)
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Ya tienes este libro en préstamo
                                    </div>
                                @else
                                    <button class="btn btn-secondary btn-lg" disabled>
                                        <i class="bi bi-x-circle me-2"></i>
                                        No disponible
                                    </button>
                                @endif
                            @endif

                            @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil me-2"></i>
                                    Editar Libro
                                </a>
                                <a href="{{ route('loans.create') }}?book_id={{ $book->id }}" class="btn btn-outline-success">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Crear Préstamo
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-check me-2"></i>
                                Iniciar sesión para préstamo
                            </a>
                        @endauth
                    </div>

                    <!-- Location Info -->
                    @if($book->location)
                    <div class="location-info mt-3">
                        <h6 class="fw-semibold">
                            <i class="bi bi-geo-alt me-1"></i>
                            Ubicación
                        </h6>
                        <p class="text-muted">{{ $book->location }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Book Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <!-- Title and Category -->
                    <div class="mb-4">
                        <h1 class="h3 text-primary mb-2">{{ $book->title }}</h1>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="badge bg-primary fs-6">{{ $book->category->name }}</span>
                            <span class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $book->publication_date ? $book->publication_date->format('Y') : 'Sin fecha' }}
                            </span>
                        </div>
                    </div>

                    <!-- Book Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold text-muted">Autor:</td>
                                    <td>{{ $book->author }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">ISBN:</td>
                                    <td><code>{{ $book->isbn }}</code></td>
                                </tr>
                                @if($book->publisher)
                                <tr>
                                    <td class="fw-semibold text-muted">Editorial:</td>
                                    <td>{{ $book->publisher }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="fw-semibold text-muted">Idioma:</td>
                                    <td>{{ $book->language }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                @if($book->pages)
                                <tr>
                                    <td class="fw-semibold text-muted">Páginas:</td>
                                    <td>{{ number_format($book->pages) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="fw-semibold text-muted">Stock total:</td>
                                    <td>{{ $book->stock_quantity }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Disponibles:</td>
                                    <td>
                                        <span class="badge bg-{{ $book->available_quantity > 0 ? 'success' : 'secondary' }}">
                                            {{ $book->available_quantity }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">En préstamo:</td>
                                    <td>{{ $book->stock_quantity - $book->available_quantity }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($book->description)
                    <div class="mb-4">
                        <h5 class="fw-semibold mb-3">Descripción</h5>
                        <p class="text-muted lh-base">{{ $book->description }}</p>
                    </div>
                    @endif

                    <!-- Active Loans Section (for librarians/admins) -->
                    @auth
                        @if((auth()->user()->isAdmin() || auth()->user()->isLibrarian()) && $book->activeLoans->count() > 0)
                        <div class="mb-4">
                            <h5 class="fw-semibold mb-3">
                                <i class="bi bi-clock-history me-2"></i>
                                Préstamos Activos ({{ $book->activeLoans->count() }})
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Fecha préstamo</th>
                                            <th>Fecha vencimiento</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($book->activeLoans as $loan)
                                        <tr>
                                            <td>
                                                <strong>{{ $loan->user->name }}</strong><br>
                                                <small class="text-muted">{{ $loan->user->student_code }}</small>
                                            </td>
                                            <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                            <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                                            <td>
                                                @if($loan->isOverdue())
                                                    <span class="badge bg-danger">Vencido</span>
                                                @else
                                                    <span class="badge bg-success">Activo</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loan Request Modal -->
@auth
    @if(auth()->user()->isStudent() && $book->isAvailable() && !$userHasActiveLoan)
    <div class="modal fade" id="loanRequestModal" tabindex="-1" aria-labelledby="loanRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('books.request-loan', $book) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="loanRequestModalLabel">
                            <i class="bi bi-bookmark-plus me-2"></i>
                            Solicitar Préstamo
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="book-summary mb-3">
                            <h6 class="fw-semibold">Libro seleccionado:</h6>
                            <p class="text-primary mb-1">{{ $book->title }}</p>
                            <p class="text-muted small">por {{ $book->author }}</p>
                        </div>

                        <div class="loan-terms bg-light p-3 rounded mb-3">
                            <h6 class="fw-semibold mb-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Términos del préstamo:
                            </h6>
                            <ul class="small mb-0">
                                <li>Duración: <strong>14 días</strong></li>
                                <li>Fecha de vencimiento: <strong>{{ Carbon\Carbon::now()->addDays(14)->format('d/m/Y') }}</strong></li>
                                <li>Renovaciones permitidas: <strong>1 vez</strong> (si no hay reservas)</li>
                                <li>Multa por retraso: <strong>S/ 1.00 por día</strong></li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notas adicionales (opcional)</label>
                            <textarea class="form-control"
                                      id="notes"
                                      name="notes"
                                      rows="3"
                                      placeholder="Motivo del préstamo, proyecto, etc..."></textarea>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="acceptTerms" required>
                            <label class="form-check-label" for="acceptTerms">
                                Acepto los términos y condiciones del préstamo
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>
                            Confirmar Préstamo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endauth

<style>
.book-detail-card {
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: none;
    border-radius: 12px;
}

.book-cover-large {
    text-align: center;
    padding: 2rem;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
}

.book-cover-large img {
    max-width: 100%;
    max-height: 400px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.availability-section .alert {
    border: none;
    border-radius: 8px;
}

.location-info {
    border-top: 1px solid #e2e8f0;
    padding-top: 1rem;
}

.book-summary {
    border-left: 4px solid var(--usmp-primary);
    padding-left: 1rem;
}

.loan-terms {
    border: 1px solid #e2e8f0;
}

@media (max-width: 768px) {
    .book-cover-large {
        padding: 1rem;
    }

    .book-cover-large img {
        max-height: 300px;
    }
}
</style>
@endsection
