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
                    <h1 class="welcome-title">Mis Metas</h1>
                    <p class="welcome-subtitle">Gestiona y alcanza tus objetivos de bienestar</p>
                </div>
                <a href="{{ route('metas.create') }}" class="btn-action-primary">
                    <i class="fas fa-plus"></i>Nueva Meta
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card-unified info">
                <div class="stat-icon-large">
                    <i class="fas fa-flag"></i>
                </div>
                <h3 class="stat-number-large">{{ $estadisticas['total'] }}</h3>
                <p class="stat-label-large">Metas Totales</p>
            </div>
            
            <div class="stat-card-unified success">
                <div class="stat-icon-large">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="stat-number-large">{{ $estadisticas['completadas'] }}</h3>
                <p class="stat-label-large">Completadas</p>
            </div>
            
            <div class="stat-card-unified primary">
                <div class="stat-icon-large">
                    <i class="fas fa-spinner"></i>
                </div>
                <h3 class="stat-number-large">{{ $estadisticas['en_progreso'] }}</h3>
                <p class="stat-label-large">En Progreso</p>
            </div>
            
            <div class="stat-card-unified warning">
                <div class="stat-icon-large">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="stat-number-large">{{ $estadisticas['pendientes'] }}</h3>
                <p class="stat-label-large">Pendientes</p>
            </div>
        </div>

        <!-- Lista de Metas -->
        <div class="content-section">
            <div class="section-header">
                <h5 class="section-title">
                    <i class="fas fa-list me-2"></i>Todas las Metas
                </h5>
            </div>
            <div class="section-body">
                @if($metas->count() > 0)
                    <div class="metas-grid">
                        @foreach($metas as $meta)
                        <div class="meta-card">
                            <div class="meta-card-header">
                                <div>
                                    <h5 class="meta-title">{{ $meta->titulo }}</h5>
                                    <span class="badge bg-{{ $meta->getColorPrioridad() }} me-2">
                                        {{ ucfirst($meta->prioridad) }}
                                    </span>
                                    <span class="badge bg-{{ $meta->getColorEstado() }}">
                                        {{ ucfirst(str_replace('_', ' ', $meta->estado)) }}
                                    </span>
                                </div>
                                <div class="meta-actions">
                                    <a href="{{ route('metas.show', $meta) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('metas.edit', $meta) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                            
                            @if($meta->descripcion)
                            <p class="meta-description">{{ Str::limit($meta->descripcion, 100) }}</p>
                            @endif
                            
                            <div class="meta-progress">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $meta->getColorEstado() }}" 
                                         role="progressbar" 
                                         style="width: {{ $meta->progreso }}%"
                                         aria-valuenow="{{ $meta->progreso }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted mt-1 d-block">{{ $meta->progreso }}% completado</small>
                            </div>
                            
                            <div class="meta-footer">
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i>
                                    {{ $meta->fecha_inicio->format('d/m/Y') }} - {{ $meta->fecha_fin->format('d/m/Y') }}
                                </small>
                                @if($meta->estaVencida())
                                <span class="badge bg-danger">Vencida</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-bullseye"></i>
                        <h5>No tienes metas creadas</h5>
                        <p>Comienza a establecer tus objetivos de bienestar</p>
                        <a href="{{ route('metas.create') }}" class="btn-action-primary">
                            <i class="fas fa-plus"></i>Crear Primera Meta
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.metas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.meta-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.meta-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.meta-card-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1rem;
}

.meta-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.5rem;
}

.meta-actions {
    display: flex;
    gap: 0.5rem;
}

.meta-description {
    color: #6c757d;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.meta-progress {
    margin-bottom: 1rem;
}

.meta-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}
</style>
@endsection