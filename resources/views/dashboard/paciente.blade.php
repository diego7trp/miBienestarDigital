@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar temporal -->
        <div class="col-md-3 bg-dark text-white p-3">
            <h5>Paciente</h5>
            <hr>
            <nav>
                <a href="{{ route('dashboard') }}" class="d-block text-white mb-2">
                    <i class="fas fa-home me-2"></i>Inicio
                </a>
                <a href="{{ route('rutinas.index') }}" class="d-block text-white mb-2">
                    <i class="fas fa-tasks me-2"></i>Rutinas
                </a>
                @if(Route::has('tareas.index'))
                <a href="{{ route('tareas.index') }}" class="d-block text-white mb-2">
                    <i class="fas fa-clipboard-list me-2"></i>Tareas
                </a>
                @endif
            </nav>
        </div>
        
        <!-- Contenido principal -->
        <div class="col-md-9 p-4">
            <h1>Bienvenida, {{ Auth::user()->nombre }}</h1>
            <p class="text-muted">Panel de control de bienestar digital</p>
            
            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3>{{ $estadisticas['rutinas_total'] }}</h3>
                            <p class="text-muted">Rutinas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3>{{ $estadisticas['tareas_pendientes'] }}</h3>
                            <p class="text-muted">Tareas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3>{{ $estadisticas['metas_cumplidas'] }}</h3>
                            <p class="text-muted">Metas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3>{{ $estadisticas['habitos_activos'] }}</h3>
                            <p class="text-muted">Hábitos</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Acciones rápidas -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Rutinas Pendientes</h5>
                            @if($rutinasPendientes->count() > 0)
                                @foreach($rutinasPendientes as $rutina)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>{{ $rutina->nombre }}</span>
                                        <button class="btn btn-sm btn-success">✓</button>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">No hay rutinas pendientes</p>
                            @endif
                            <a href="{{ route('rutinas.index') }}" class="btn btn-warning w-100 mt-2">Ver Todas</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Crear Nueva Tarea</h5>
                            <p class="text-muted">Organiza tu día con nuevas tareas</p>
                            @if(Route::has('tareas.create'))
                                <a href="{{ route('tareas.create') }}" class="btn btn-warning w-100">Crear Tarea</a>
                            @else
                                <button class="btn btn-warning w-100">Próximamente</button>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Mi Perfil</h5>
                            <p class="text-muted">Revisa tu información personal</p>
                            <button class="btn btn-warning w-100">Ver Perfil</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection