@extends('layouts.app')

@section('page_title', 'Nuevo Préstamo')

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
            <li class="breadcrumb-item active">Nuevo Préstamo</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        Crear Nuevo Préstamo
                    </h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('loans.store') }}" method="POST" id="loanForm">
                        @csrf

                        <div class="row">
                            <!-- Selección de Estudiante -->
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label required">Estudiante</label>
                                <select class="form-select @error('user_id') is-invalid @enderror"
                                        id="user_id"
                                        name="user_id"
                                        required>
                                    <option value="">Seleccionar estudiante...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                                {{ (old('user_id', $selectedUserId) == $user->id) ? 'selected' : '' }}
                                                data-student-code="{{ $user->student_code }}"
                                                data-active-loans="{{ $user->activeLoans()->count() }}"
                                                data-student-name="{{ $user->name }}">
                                            {{ $user->name }} ({{ $user->student_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- Student Info Panel -->
                                <div id="studentInfo" class="mt-2 p-3 bg-light rounded" style="display: none;">
                                    <h6 class="fw-semibold mb-2">Información del Estudiante</h6>
                                    <div><strong>Nombre:</strong> <span id="infoName"></span></div>
                                    <div><strong>Código:</strong> <span id="infoCode"></span></div>
                                    <div><strong>Préstamos activos:</strong> <span id="infoLoans"></span></div>
                                </div>
                            </div>

                            <!-- Selección de Libro -->
                            <div class="col-md-6 mb-3">
                                <label for="book_id" class="form-label required">Libro</label>
                                <select class="form-select @error('book_id') is-invalid @enderror"
                                        id="book_id"
                                        name="book_id"
                                        required>
                                    <option value="">Seleccionar libro...</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}"
                                                {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                            {{ $book->title }} ({{ $book->author }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('book_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Fecha de Préstamo -->
                            <div class="col-md-6 mb-3">
                                <label for="loan_date" class="form-label required">Fecha de Préstamo</label>
                                <input type="date"
                                       class="form-control @error('loan_date') is-invalid @enderror"
                                       id="loan_date"
                                       name="loan_date"
                                       value="{{ old('loan_date', date('Y-m-d')) }}"
                                       required>
                                @error('loan_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha de Devolución -->
                            <div class="col-md-6 mb-3">
                                <label for="return_date" class="form-label required">Fecha de Devolución</label>
                                <input type="date"
                                       class="form-control @error('return_date') is-invalid @enderror"
                                       id="return_date"
                                       name="return_date"
                                       value="{{ old('return_date') }}"
                                       required>
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('loans.index') }}" class="btn btn-secondary me-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Guardar Préstamo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const userSelect = document.getElementById('user_id');
    const infoPanel = document.getElementById('studentInfo');
    const infoName = document.getElementById('infoName');
    const infoCode = document.getElementById('infoCode');
    const infoLoans = document.getElementById('infoLoans');

    function updateStudentInfo() {
        const selected = userSelect.options[userSelect.selectedIndex];
        if (selected && selected.value) {
            infoName.textContent = selected.getAttribute('data-student-name');
            infoCode.textContent = selected.getAttribute('data-student-code');
            infoLoans.textContent = selected.getAttribute('data-active-loans');
            infoPanel.style.display = 'block';
        } else {
            infoPanel.style.display = 'none';
        }
    }

    userSelect.addEventListener('change', updateStudentInfo);
    updateStudentInfo();
});
</script>
@endpush
