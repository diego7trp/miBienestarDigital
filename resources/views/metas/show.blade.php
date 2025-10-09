@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar-dashboard">
        <div class="sidebar-header">
            <h4 class="sidebar-title">Paciente</h4>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-link-custom">
                <i class="fas fa-home"></i>Inicio
            </a>
            <a href="{{ route('rutinas.index') }}" class="nav-link-custom">
                <i class="fas fa-calendar-day"></i>Mis Rutinas
            </a>
            @if(Route::has('tareas.index'))
            <a href="{{ route('tareas.index') }}" class="nav-link-custom">
                <i class="fas fa-tasks"></i>Tareas
            </a>
            @endif
            <a href="{{ route('metas.index') }}" class="nav-link-custom active">
                <i class="fas fa-bullseye"></i>Metas
            </a>
            <a href="#" class="nav-link-custom">
                <i class="fas fa-lightbulb"></i>Consejos
            </a>
            
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem;">
            
            <a href="{{ route('logout') }}" class="nav-link-custom" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>Cerrar Sesión
            </a>
        </nav>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <!-- Header -->
        <div class="content-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 class="welcome-title">{{ $meta->titulo }}</h1>
                    <p class="welcome-subtitle">Detalle de tu meta</p>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('metas.edit', $meta) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('metas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Información de la Meta -->
        <div class="content-section">
            <div class="section-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>Información General</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Estado:</th>
                                <td>
                                    <span class="badge bg-{{ $meta->getColorEstado() }} fs-6">
                                        {{ ucfirst(str_replace('_', ' ', $meta->estado)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Prioridad:</th>
                                <td>
                                    <span class="badge bg-{{ $meta->getColorPrioridad() }} fs-6">
                                        {{ ucfirst($meta->prioridad) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Categoría:</th>
                                <td>{{ ucfirst($meta->categoria) }}</td>
                            </tr>
                            <tr>
                                <th>Fecha Inicio:</th>
                                <td>{{ $meta->fecha_inicio->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Fecha Fin:</th>
                                <td>
                                    {{ $meta->fecha_fin->format('d/m/Y') }}
                                    @if($meta->estaVencida())
                                        <span class="badge bg-danger ms-2">Vencida</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        @if($meta->descripcion)
                        <div class="mt-4">
                            <h5>Descripción</h5>
                            <p class="text-muted">{{ $meta->descripcion }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Progreso</h5>
                                <div class="progress mb-3" style="height: 30px;">
                                    <div class="progress-bar bg-{{ $meta->getColorEstado() }}" 
                                         role="progressbar" 
                                         style="width: {{ $meta->progreso }}%; font-size: 16px;"
                                         aria-valuenow="{{ $meta->progreso }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $meta->progreso }}%
                                    </div>
                                </div>

                                <form action="{{ route('metas.progreso', $meta) }}" method="POST" class="mt-3">
                                    @csrf
                                    <label for="progreso" class="form-label">Actualizar progreso</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control" 
                                               id="progreso" 
                                               name="progreso" 
                                               value="{{ $meta->progreso }}"
                                               min="0" 
                                               max="100"
                                               required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2 w-100">
                                        <i class="fas fa-save"></i> Actualizar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-3">
                            <form action="{{ route('metas.destroy', $meta) }}" method="POST" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta meta?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash"></i> Eliminar Meta
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection