@extends('layouts.app')

@section('page_title', 'Editar Categoría - ' . $category->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item">
                                <a href="{{ route('categories.index') }}" class="text-decoration-none">
                                    Categorías
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                                    {{ $category->name }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </nav>
                    <h2 class="mb-1">Editar Categoría</h2>
                    <p class="text-muted mb-0">Modifica la información de "{{ $category->name }}"</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-info">
                        <i class="bi bi-eye me-1"></i>
                        Ver Detalles
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Volver a Lista
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Edit Form -->
                <div class="col-lg-8 col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <div class="d-flex align-items-center">
                                <div class="category-icon me-3">
                                    <i class="bi bi-pencil text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0">Formulario de Edición</h5>
                                    <small class="text-muted">Actualiza los datos de la categoría</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('categories.update', $category) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="bi bi-tag me-1"></i>
                                        Nombre de la Categoría <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $category->name) }}"
                                        placeholder="Ejemplo: Literatura, Ciencias, Historia..." maxlength="100"
                                        required autocomplete="off">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Máximo 100 caracteres. Debe ser único.
                                    </div>
                                </div>

                                <!-- Description Field -->
                                <div class="mb-4">
                                    <label for="description" class="form-label fw-semibold">
                                        <i class="bi bi-card-text me-1"></i>
                                        Descripción <span class="text-muted">(Opcional)</span>
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" rows="3"
                                        placeholder="Descripción breve de la categoría..."
                                        maxlength="255">{{ old('description', $category->description) }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Descripción opcional para ayudar a identificar la categoría.
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-1"></i>
                                        Actualizar Categoría
                                    </button>
                                    <a href="{{ route('categories.show', $category) }}"
                                        class="btn btn-outline-secondary">
                                        <i class="bi bi-x-lg me-1"></i>
                                        Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Warning Card -->
                    @if($category->books_count > 0)
                    <div class="card border-warning bg-warning bg-opacity-10 mt-3">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div class="text-warning me-2">
                                    <i class="bi bi-exclamation-triangle fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-warning">Categoría con libros asociados</h6>
                                    <p class="mb-0 small text-warning-emphasis">
                                        Esta categoría tiene <strong>{{ $category->books_count }} libro(s)</strong>
                                        asociado(s).
                                        Los cambios en el nombre se reflejarán en todos los libros de esta categoría.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Info Card -->
                    <div class="card border-0 bg-light mt-3">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div class="text-primary me-2">
                                    <i class="bi bi-lightbulb fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Consejos para editar categorías</h6>
                                    <ul class="mb-0 small text-muted">
                                        <li>Mantén los nombres descriptivos y concisos</li>
                                        <li>Asegúrate de que el nuevo nombre no genere duplicados</li>
                                        <li>Considera el impacto en los libros ya categorizados</li>
                                        <li>La descripción ayuda a otros usuarios a entender la categoría</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Info Sidebar -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2 text-primary"></i>
                                Información Actual
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="category-icon-large mx-auto mb-3">
                                    <i class="bi bi-tag text-primary"></i>
                                </div>
                                <h5 class="mb-1">{{ $category->name }}</h5>
                                @if($category->description)
                                <p class="text-muted small">{{ $category->description }}</p>
                                @else
                                <p class="text-muted small fst-italic">Sin descripción</p>
                                @endif
                            </div>

                            <div class="row text-center mb-4">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h4 class="text-primary mb-1">{{ $category->books_count ?? 0 }}</h4>
                                        <small class="text-muted">Libros</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success mb-1">
                                        {{ $category->created_at->diffForHumans() }}
                                    </h4>
                                    <small class="text-muted">Creada</small>
                                </div>
                            </div>

                            <div class="border-top pt-3">
                                <div class="row g-2 text-sm">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small">
                                                <i class="bi bi-calendar-plus me-1"></i>
                                                Creada:
                                            </span>
                                            <span class="small">
                                                {{ $category->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">
                                                <i class="bi bi-arrow-repeat me-1"></i>
                                                Actualizada:
                                            </span>
                                            <span class="small">
                                                {{ $category->updated_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header bg-white border-0">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-lightning me-2 text-warning"></i>
                                Acciones Rápidas
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('categories.show', $category) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>
                                    Ver Detalles Completos
                                </a>
                                @if($category->books_count > 0)
                                <a href="{{ route('books.index', ['category' => $category->id]) }}"
                                    class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-book me-1"></i>
                                    Ver Libros ({{ $category->books_count }})
                                </a>
                                @endif
                                <a href="{{ route('books.create', ['category' => $category->id]) }}"
                                    class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Agregar Libro
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="card border-danger mt-3">
                        <div class="card-header bg-danger bg-opacity-10 border-danger">
                            <h6 class="card-title mb-0 text-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Zona de Peligro
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted mb-3">
                                Una vez eliminada, esta acción no se puede deshacer.
                                @if($category->books_count > 0)
                                <strong class="text-warning">
                                    Ten en cuenta que hay {{ $category->books_count }} libro(s) asociado(s).
                                </strong>
                                @endif
                            </p>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                onsubmit="return confirm('¿Estás absolutamente seguro de eliminar la categoría \'{{ $category->name }}\'?{{ $category->books_count > 0 ? ' Esto afectará a ' . $category->books_count . ' libro(s).' : '' }} Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                    <i class="bi bi-trash me-1"></i>
                                    Eliminar Categoría
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .category-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: rgba(var(--bs-primary-rgb), 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .category-icon-large {
        width: 70px;
        height: 70px;
        border-radius: 15px;
        background: rgba(var(--bs-primary-rgb), 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .category-icon-large i {
        font-size: 2rem;
    }

    .card {
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
    }

    .btn {
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: "›";
        font-weight: bold;
    }

    .border-end {
        border-right: 1px solid #dee2e6 !important;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .border-end {
            border-right: none !important;
            border-bottom: 1px solid #dee2e6 !important;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
    }

    /* Form validation styles */
    .was-validated .form-control:valid {
        border-color: #198754;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.4-.4c.2-.2.2-.6 0-.8L1.6 4.46c-.3-.3-.8-.3-1.1 0s-.3.8 0 1.1l.4.4c.2.2.6.2.8 0 .2-.2.2-.6 0-.8L.5 3.9c-.3-.3-.3-.8 0-1.1s.8-.3 1.1 0l.4.4c.2.2.6.2.8 0s.2-.6 0-.8L1.6 1.25c-.3-.3-.8-.3-1.1 0s-.3.8 0 1.1l.4.4c.2.2.2.6 0 .8s-.6.2-.8 0l-.4-.4c-.3-.3-.8-.3-1.1 0s-.3.8 0 1.1l1.8 1.8c.3.3.8.3 1.1 0s.3-.8 0-1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .was-validated .form-control:invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6a.8.8 0 0 1 .4-.3c.2 0 .4.1.6.3.2.2.3.4.3.6 0 .2-.1.4-.3.6l-.6.6-.6.6c-.2.2-.4.3-.6.3-.2 0-.4-.1-.6-.3-.2-.2-.3-.4-.3-.6 0-.2.1-.4.3-.6l.6-.6.6-.6z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Add form validation
    const form = document.querySelector('form');
    const nameInput = document.getElementById('name');

    // Real-time validation feedback
    nameInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
        } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        form.classList.add('was-validated');

        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();

            // Focus on first invalid field
            const firstInvalid = form.querySelector('.form-control:invalid');
            if (firstInvalid) {
                firstInvalid.focus();
            }
        }
    });

    // Character counter for name field
    const nameCounter = document.createElement('div');
    nameCounter.className = 'form-text text-end';
    nameCounter.style.marginTop = '-1rem';
    nameCounter.style.marginBottom = '1rem';
    nameInput.parentNode.appendChild(nameCounter);

    function updateNameCounter() {
        const remaining = 100 - nameInput.value.length;
        nameCounter.textContent = `${remaining} caracteres restantes`;
        nameCounter.className = `form-text text-end ${remaining < 20 ? 'text-warning' : remaining < 10 ? 'text-danger' : 'text-muted'}`;
    }

    nameInput.addEventListener('input', updateNameCounter);
    updateNameCounter(); // Initial count

    // Character counter for description field
    const descInput = document.getElementById('description');
    const descCounter = document.createElement('div');
    descCounter.className = 'form-text text-end';
    descCounter.style.marginTop = '-1rem';
    descCounter.style.marginBottom = '1rem';
    descInput.parentNode.appendChild(descCounter);

    function updateDescCounter() {
        const remaining = 255 - descInput.value.length;
        descCounter.textContent = `${remaining} caracteres restantes`;
        descCounter.className = `form-text text-end ${remaining < 50 ? 'text-warning' : remaining < 20 ? 'text-danger' : 'text-muted'}`;
    }

    descInput.addEventListener('input', updateDescCounter);
    updateDescCounter(); // Initial count
});
</script>
@endpush
@endsection
