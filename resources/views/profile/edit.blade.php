@extends('layouts.app')

@section('page_title', 'Editar Perfil')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-gear me-2"></i>
                        Editar Perfil
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Módulo en Desarrollo:</strong> La funcionalidad de edición de perfil estará disponible próximamente.
                    </div>

                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="user-avatar-large mx-auto mb-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nombre:</strong></td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                @if($user->student_code)
                                <tr>
                                    <td><strong>Código de Estudiante:</strong></td>
                                    <td>{{ $user->student_code }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Rol:</strong></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ ucfirst($user->role ?? 'student') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Préstamos Activos:</strong></td>
                                    <td>{{ $user->activeLoans()->count() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total de Préstamos:</strong></td>
                                    <td>{{ $user->loans()->count() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('books.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Volver al Catálogo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.user-avatar-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--usmp-primary), var(--usmp-accent));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
    font-weight: 700;
}
</style>
@endsection
