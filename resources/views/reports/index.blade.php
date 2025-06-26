@extends('layouts.app')

@section('title', 'Reportes - Sistema Biblioteca UNMSM')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Reportes y Estadísticas
                    </h1>
                    <p class="text-muted mb-0">Análisis y reportes del sistema de biblioteca</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>
                        Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas generales -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0" id="total-books">{{ App\Models\Book::count() }}</h4>
                            <small>Total de Libros</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0" id="active-loans">{{ App\Models\Loan::where('status', 'active')->count() }}
                            </h4>
                            <small>Préstamos Activos</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-handshake fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0" id="overdue-loans">{{ App\Models\Loan::where('status',
                                'active')->where('due_date', '<', now())->count() }}</h4>
                            <small>Préstamos Vencidos</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0" id="total-users">{{ App\Models\User::where('role', 'student')->count() }}
                            </h4>
                            <small>Estudiantes Registrados</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reportes disponibles -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Reportes Disponibles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Reporte de Préstamos -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Préstamos
                                            </div>
                                            <div class="h6 mb-2 font-weight-bold text-gray-800">
                                                Reporte de Préstamos
                                            </div>
                                            <div class="text-muted small">
                                                Análisis detallado de todos los préstamos con filtros por fecha y
                                                estado.
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('reports.loans') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                            Ver Reporte
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Libros Más Populares -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border-left-success h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Popularidad
                                            </div>
                                            <div class="h6 mb-2 font-weight-bold text-gray-800">
                                                Libros Más Prestados
                                            </div>
                                            <div class="text-muted small">
                                                Ranking de libros más solicitados y estadísticas de popularidad.
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-star fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('reports.popular-books') }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                            Ver Reporte
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Usuarios Activos -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border-left-info h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Usuarios
                                            </div>
                                            <div class="h6 mb-2 font-weight-bold text-gray-800">
                                                Usuarios Más Activos
                                            </div>
                                            <div class="text-muted small">
                                                Estadísticas de usuarios con mayor actividad de préstamos.
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('reports.active-users') }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                            Ver Reporte
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categorías Más Prestadas -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border-left-warning h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Categorías
                                            </div>
                                            <div class="h6 mb-2 font-weight-bold text-gray-800">
                                                Categorías Más Prestadas
                                            </div>
                                            <div class="text-muted small">
                                                Ranking de categorías con mayor número de préstamos.
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('reports.categories') }}"
                                            class="btn btn-warning btn-sm text-white">
                                            <i class="fas fa-eye me-1"></i>
                                            Ver Reporte
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Préstamos Vencidos -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border-left-danger h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Vencidos
                                            </div>
                                            <div class="h6 mb-2 font-weight-bold text-gray-800">
                                                Préstamos Vencidos
                                            </div>
                                            <div class="text-muted small">
                                                Listado y análisis de préstamos vencidos.
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('reports.overdue-loans') }}" class="btn btn-danger btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                            Ver Reporte
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inventario de Libros -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border-left-secondary h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                Inventario
                                            </div>
                                            <div class="h6 mb-2 font-weight-bold text-gray-800">
                                                Inventario de Libros
                                            </div>
                                            <div class="text-muted small">
                                                Consulta y exporta el inventario de libros por categoría y
                                                disponibilidad.
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-archive fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('reports.inventory') }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                            Ver Reporte
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- row -->
                </div> <!-- card-body -->
            </div> <!-- card -->
        </div> <!-- col-12 -->
    </div> <!-- row -->
</div>
@endsection