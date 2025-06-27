@extends('layouts.app')

@section('page_title', 'Nueva Categoría')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Nueva Categoría</h2>
                    <p class="text-muted mb-0">Crear una nueva categoría para clasificar los libros</p>
                </div>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Volver
                </a>
            </div>

            <!-- Form Card -->
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form action="{{ route('categories.store') }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="bi bi-tag me-1"></i>
                                        Nombre de la Categoría <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}"
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

                                <!-- Optional Description Field (if you want to add it to your model later) -->
                                <div class="mb-4">
                                    <label for="description" class="form-label fw-semibold">
                                        <i class="bi bi-card-text me-1"></i>
                                        Descripción <span class="text-muted">(Opcional)</span>
                                    </label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        placeholder="Descripción breve de la categoría..."
                                        maxlength="255">{{ old('description') }}</textarea>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Descripción opcional para ayudar a identificar la categoría.
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-1"></i>
                                        Crear Categoría
                                    </button>
                                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-lg me-1"></i>
                                        Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="card border-0 bg-light mt-3">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div class="text-primary me-2">
                                    <i class="bi bi-lightbulb fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Consejos para crear categorías</h6>
                                    <ul class="mb-0 small text-muted">
                                        <li>Usa nombres descriptivos y concisos</li>
                                        <li>Evita duplicados o categorías muy similares</li>
                                        <li>Considera cómo los usuarios buscarán los libros</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
</style>
@endsection
