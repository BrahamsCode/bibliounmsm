@extends('layouts.app')

@section('title', $book->title . ' - BiblioUNMSM')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Catálogo</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($book->title, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <img src="{{ $book->cover_image ?? 'https://via.placeholder.com/400x600' }}"
                     alt="{{ $book->title }}" class="card-img-top">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        @if($book->isAvailable())
                            <span class="badge bg-success fs-6">Disponible</span>
                        @else
                            <span class="badge bg-danger fs-6">No disponible</span>
                        @endif
                        <span class="text-muted">{{ $book->available_quantity }}/{{ $book->stock_quantity }}</span>
                    </div>

                    @auth
                        @if(Auth::user()->isLibrarian() || Auth::user()->isAdmin())
                        <div class="d-grid gap-2">
                            <a href="{{ route('books.edit', $book) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Editar
                            </a>
                            @if($book->isAvailable())
                            <a href="{{ route('loans.create', ['book_id' => $book->id]) }}" class="btn btn-success">
                                <i class="fas fa-handshake me-2"></i>Crear Préstamo
                            </a>
                            @endif
                        </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h1 class="card-title">{{ $book->title }}</h1>
                    <p class="text-muted fs-5 mb-4">Por: {{ $book->author }}</p>

                    @if($book->description)
                    <div class="mb-4">
                        <h5>Descripción</h5>
                        <p class="text-muted">{{ $book->description }}</p>
                    </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Información del Libro</h6>
                            <ul class="list-unstyled">
                                <li><strong>ISBN:</strong> {{ $book->isbn }}</li>
                                <li><strong>Editorial:</strong> {{ $book->publisher ?? 'N/A' }}</li>
                                <li><strong>Fecha de Publicación:</strong> {{ $book->publication_date ? $book->publication_date->format('d/m/Y') : 'N/A' }}</li>
                                <li><strong>Páginas:</strong> {{ $book->pages ?? 'N/A' }}</li>
                                <li><strong>Idioma:</strong> {{ ucfirst($book->language) }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Disponibilidad</h6>
                            <ul class="list-unstyled">
                                <li><strong>Categoría:</strong> {{ $book->category->name }}</li>
                                <li><strong>Ubicación:</strong> {{ $book->location ?? 'N/A' }}</li>
                                <li><strong>Stock Total:</strong> {{ $book->stock_quantity }}</li>
                                <li><strong>Disponibles:</strong> {{ $book->available_quantity }}</li>
                                <li><strong>En Préstamo:</strong> {{ $book->stock_quantity - $book->available_quantity }}</li>
                            </ul>
                        </div>
                    </div>

                    @if($book->activeLoans->count() > 0)
                    <div class="mb-4">
                        <h6>Préstamos Activos</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Usuario</th>
                                        <th>Fecha de Préstamo</th>
                                        <th>Fecha de Vencimiento</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($book->activeLoans as $loan)
                                    <tr>
                                        <td>{{ $loan->loan_code }}</td>
                                        <td>{{ $loan->user->name }}</td>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
