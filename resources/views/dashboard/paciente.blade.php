@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar-dashboard">
        <div class="sidebar-header">
            <h4 class="sidebar-title">Paciente</h4>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>Inicio
            </a>
            <a href="{{ route('rutinas.index') }}" class="nav-link-custom {{ request()->routeIs('rutinas.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-day"></i>Mis Rutinas
            </a>
            @if(Route::has('tareas.index'))
            <a href="{{ route('tareas.index') }}" class="nav-link-custom {{ request()->routeIs('tareas.*') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>Tareas
            </a>
            @else
            <a href="#" class="nav-link-custom">
                <i class="fas fa-tasks"></i>Tareas
            </a>
            @endif
            <a href="#" class="nav-link-custom">
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
        <!-- Header de Bienvenida -->
        <div class="content-header">
            <h1 class="welcome-title">Bienvenido, {{ Auth::user()->nombre ?? 'Ana' }}</h1>
            <p class="welcome-subtitle">Este es tu panel de paciente. Consulta tus actividades diarias, metas y consejos.</p>
        </div>

        <!-- Cards Principales -->
        <div class="action-cards">
            <!-- Mi Rutina de Hoy -->
            <div class="action-card">
                <div class="action-card-header">
                    <h5 class="action-card-title">Mi Rutina de Hoy</h5>
                    <p class="action-card-subtitle">Ejercicio, desayuno saludable y respiración consciente.</p>
                </div>
                <div class="action-card-footer">
                    <a href="{{ route('rutinas.index') }}" class="btn-action-primary">
                        <i class="fas fa-eye"></i>Ver Rutina
                    </a>
                </div>
            </div>

            <!-- Consejo del Día -->
            <div class="action-card">
                <div class="action-card-header">
                    <h5 class="action-card-title">Consejo del Día</h5>
                    <p class="action-card-subtitle">"Evita el sedentarismo: camina al menos 30 minutos."</p>
                </div>
                <div class="action-card-footer">
                    <a href="#" class="btn-action-primary">
                        <i class="fas fa-plus"></i>Ver Más
                    </a>
                </div>
            </div>

            <!-- Progreso de Metas -->
            <div class="action-card">
                <div class="action-card-header">
                    <h5 class="action-card-title">Progreso de Metas</h5>
                    <p class="action-card-subtitle">{{ isset($estadisticas['metas_cumplidas']) ? $estadisticas['metas_cumplidas'] : '2' }} de 4 metas cumplidas este mes.</p>
                </div>
                <div class="action-card-footer">
                    <a href="#" class="btn-action-primary">
                        <i class="fas fa-chart-line"></i>Ver Detalles
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas (opcional - solo si tienes datos) -->
        @if(isset($estadisticas))
        <div class="stats-grid">
            <div class="stat-card-unified success">
                <div class="stat-icon-large">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="stat-number-large">{{ $estadisticas['rutinas_total'] ?? 0 }}</h3>
                <p class="stat-label-large">Rutinas Totales</p>
            </div>
            
            <div class="stat-card-unified primary">
                <div class="stat-icon-large">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3 class="stat-number-large">{{ $estadisticas['tareas_pendientes'] ?? 0 }}</h3>
                <p class="stat-label-large">Tareas Pendientes</p>
            </div>
            
            <div class="stat-card-unified warning">
                <div class="stat-icon-large">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="stat-number-large">{{ $estadisticas['metas_cumplidas'] ?? 0 }}</h3>
                <p class="stat-label-large">Metas Cumplidas</p>
            </div>
            
            <div class="stat-card-unified info">
                <div class="stat-icon-large">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 class="stat-number-large">{{ $estadisticas['habitos_activos'] ?? 0 }}</h3>
                <p class="stat-label-large">Hábitos</p>
            </div>
        </div>

        <!-- Rutinas Pendientes (opcional) -->
        <div class="content-section">
            <div class="section-header">
                <h5 class="section-title">
                    <i class="fas fa-clock me-2"></i>Actividades de Hoy
                </h5>
            </div>
            <div class="section-body">
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <h5>¡Excelente trabajo!</h5>
                    <p>No tienes rutinas pendientes por hoy.</p>
                    <a href="{{ route('rutinas.index') }}" class="btn-action-primary">
                        Ver Todas las Rutinas
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection