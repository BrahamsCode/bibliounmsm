@extends('layouts.app')

@section('page_title', 'Editar: ' . $book->title)

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('books.index') }}" class="text-decoration-none">
                    <i class="bi bi-book me-1"></i>Catálogo
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('books.show', $book) }}" class="text-decoration-none">
                    {{ Str::limit($book->title, 30) }}
                </a>
            </li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Editar Libro</h2>
            <p class="text-muted mb-0">Modifica la información del libro</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('books.show', $book) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil me-2 text-primary"></i>
                        Información del Libro
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Título -->
                            <div class="col-md-8">
                                <label for="title" class="form-label fw-semibold">
                                    <i class="bi bi-book me-1"></i>Título *
                                </label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title', $book->title) }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Categoría -->
                            <div class="col-md-4">
                                <label for="category_id" class="form-label fw-semibold">
                                    <i class="bi bi-tag me-1"></i>Categoría *
                                </label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id"
                                        name="category_id"
                                        required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Autor -->
                            <div class="col-md-6">
                                <label for="author" class="form-label fw-semibold">
                                    <i class="bi bi-person me-1"></i>Autor *
                                </label>
                                <input type="text"
                                       class="form-control @error('author') is-invalid @enderror"
                                       id="author"
                                       name="author"
                                       value="{{ old('author', $book->author) }}"
                                       required>
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ISBN -->
                            <div class="col-md-6">
                                <label for="isbn" class="form-label fw-semibold">
                                    <i class="bi bi-upc-scan me-1"></i>ISBN *
                                </label>
                                <input type="text"
                                       class="form-control @error('isbn') is-invalid @enderror"
                                       id="isbn"
                                       name="isbn"
                                       value="{{ old('isbn', $book->isbn) }}"
                                       required>
                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Editorial -->
                            <div class="col-md-6">
                                <label for="publisher" class="form-label fw-semibold">
                                    <i class="bi bi-building me-1"></i>Editorial
                                </label>
                                <input type="text"
                                       class="form-control @error('publisher') is-invalid @enderror"
                                       id="publisher"
                                       name="publisher"
                                       value="{{ old('publisher', $book->publisher) }}">
                                @error('publisher')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha de Publicación -->
                            <div class="col-md-6">
                                <label for="publication_date" class="form-label fw-semibold">
                                    <i class="bi bi-calendar me-1"></i>Fecha de Publicación
                                </label>
                                <input type="date"
                                       class="form-control @error('publication_date') is-invalid @enderror"
                                       id="publication_date"
                                       name="publication_date"
                                       value="{{ old('publication_date', $book->publication_date?->format('Y-m-d')) }}">
                                @error('publication_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Páginas -->
                            <div class="col-md-4">
                                <label for="pages" class="form-label fw-semibold">
                                    <i class="bi bi-file-text me-1"></i>Páginas
                                </label>
                                <input type="number"
                                       class="form-control @error('pages') is-invalid @enderror"
                                       id="pages"
                                       name="pages"
                                       value="{{ old('pages', $book->pages) }}"
                                       min="1">
                                @error('pages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Idioma -->
                            <div class="col-md-4">
                                <label for="language" class="form-label fw-semibold">
                                    <i class="bi bi-translate me-1"></i>Idioma *
                                </label>
                                <select class="form-select @error('language') is-invalid @enderror"
                                        id="language"
                                        name="language"
                                        required>
                                    <option value="">Seleccionar idioma</option>
                                    <option value="Español" {{ old('language', $book->language) == 'Español' ? 'selected' : '' }}>Español</option>
                                    <option value="Inglés" {{ old('language', $book->language) == 'Inglés' ? 'selected' : '' }}>Inglés</option>
                                    <option value="Francés" {{ old('language', $book->language) == 'Francés' ? 'selected' : '' }}>Francés</option>
                                    <option value="Portugués" {{ old('language', $book->language) == 'Portugués' ? 'selected' : '' }}>Portugués</option>
                                    <option value="Italiano" {{ old('language', $book->language) == 'Italiano' ? 'selected' : '' }}>Italiano</option>
                                    <option value="Alemán" {{ old('language', $book->language) == 'Alemán' ? 'selected' : '' }}>Alemán</option>
                                    <option value="Otro" {{ old('language', $book->language) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div class="col-md-4">
                                <label for="stock_quantity" class="form-label fw-semibold">
                                    <i class="bi bi-boxes me-1"></i>Stock Total *
                                </label>
                                <input type="number"
                                       class="form-control @error('stock_quantity') is-invalid @enderror"
                                       id="stock_quantity"
                                       name="stock_quantity"
                                       value="{{ old('stock_quantity', $book->stock_quantity) }}"
                                       min="1"
                                       required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Disponibles actualmente: {{ $book->available_quantity }}
                                </div>
                            </div>

                            <!-- Ubicación -->
                            <div class="col-12">
                                <label for="location" class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1"></i>Ubicación en Biblioteca
                                </label>
                                <input type="text"
                                       class="form-control @error('location') is-invalid @enderror"
                                       id="location"
                                       name="location"
                                       value="{{ old('location', $book->location) }}"
                                       placeholder="Ej: Estante A, Sección 3, Piso 2">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">
                                    <i class="bi bi-card-text me-1"></i>Descripción
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="4"
                                          placeholder="Descripción del contenido del libro...">{{ old('description', $book->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div class="text-muted small">
                                <i class="bi bi-asterisk me-1 text-danger"></i>
                                Los campos marcados con * son obligatorios
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Actualizar Libro
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Book Preview -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top">
                <div class="card-header bg-white border-0">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-eye me-2 text-primary"></i>
                        Vista Previa
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="book-preview-cover mb-3">
                        <img src="{{ $book->cover_image ?? 'https://via.placeholder.com/200x300/1e40af/ffffff?text=' . urlencode(substr($book->title, 0, 3)) }}"
                             alt="{{ $book->title }}"
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 250px;">
                    </div>

                    <h6 class="fw-bold text-primary">{{ $book->title }}</h6>
                    <p class="text-muted mb-2">{{ $book->author }}</p>
                    <span class="badge bg-primary">{{ $book->category->name }}</span>

                    <hr class="my-3">

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-primary fw-bold">{{ $book->stock_quantity }}</div>
                            <small class="text-muted">Stock Total</small>
                        </div>
                        <div class="col-6">
                            <div class="text-success fw-bold">{{ $book->available_quantity }}</div>
                            <small class="text-muted">Disponibles</small>
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
                        <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-1"></i>Ver Detalles
                        </a>
                        <a href="{{ route('loans.create') }}?book_id={{ $book->id }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Crear Préstamo
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-1"></i>Eliminar Libro
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el libro <strong>"{{ $book->title }}"</strong>?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>¡Atención!</strong> Esta acción no se puede deshacer.
                    @can('manage-loans')
                    @if($book->activeLoans->count() > 0)
                        <br><strong>Nota:</strong> Este libro tiene préstamos activos y no puede ser eliminado.
                    @endif
                    @endcan
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                @if($book->activeLoans->count() == 0)
                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                </form>
                @else
                <button type="button" class="btn btn-danger" disabled>
                    <i class="bi bi-x-circle me-1"></i>No se puede eliminar
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.sticky-top {
    top: 2rem;
}

.book-preview-cover {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    padding: 1rem;
    border-radius: 8px;
}

.form-label {
    color: #374151;
}

.card {
    transition: all 0.2s ease-in-out;
}

.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
}

.invalid-feedback {
    display: block;
}

@media (max-width: 992px) {
    .sticky-top {
        position: relative !important;
        top: auto !important;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-save draft functionality could be added here
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, select, textarea');

    // Add unsaved changes warning
    let hasUnsavedChanges = false;

    inputs.forEach(input => {
        input.addEventListener('change', function() {
            hasUnsavedChanges = true;
        });
    });

    form.addEventListener('submit', function() {
        hasUnsavedChanges = false;
    });

    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
});
</script>
@endpush
@endsection
