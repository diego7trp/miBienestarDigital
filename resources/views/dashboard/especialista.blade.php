@extends('layouts.dashboard')

@section('content')
<div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 p-0">
        <div class="sidebar-dashboard">
            <div class="sidebar-header">
                <h5 class="sidebar-title">Especialista</h5>
                <div class="sidebar-subtitle">Panel Profesional</div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link-custom active">
                        <i class="fas fa-home"></i>Inicio
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link-custom">
                        <i class="fas fa-comment-medical"></i>Mis Consejos
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link-custom">
                        <i class="fas fa-plus-circle"></i>Crear Consejo
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link-custom">
                        <i class="fas fa-users"></i>Pacientes
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link-custom">
                        <i class="fas fa-chart-bar"></i>Estadísticas
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link-custom">
                        <i class="fas fa-user-circle"></i>Mi Perfil
                    </a>
                </div>
                <div class="nav-item mt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link-custom w-100 border-0 bg-transparent text-start">
                            <i class="fas fa-sign-out-alt"></i>Cerrar Sesión
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>
    
    <!-- Contenido Principal -->
    <div class="col-md-9 col-lg-10">
        <div class="main-content">
            <!-- Header de Bienvenida -->
            <div class="content-header">
                <h1 class="welcome-title">Bienvenida, Dr. {{ Auth::user()->nombre }}</h1>
                <p class="welcome-subtitle">
                    Panel de control para especialistas en bienestar digital.
                </p>
            </div>

            <!-- Cards de Acción Principal -->
            <div class="action-cards">
                <!-- Último Consejo -->
                <div class="action-card">
                    <div class="action-card-header">
                        <h3 class="action-card-title">Último Consejo Publicado</h3>
                        <p class="action-card-subtitle">
                            @if($estadisticas['ultimo_consejo'])
                                "{{ Str::limit($estadisticas['ultimo_consejo']->descripcion, 80) }}"
                            @else
                                Aún no has publicado ningún consejo para los pacientes.
                            @endif
                        </p>
                    </div>
                    
                    <div class="action-card-content">
                        @if($estadisticas['ultimo_consejo'])
                            <div class="alert alert-light">
                                <strong>Título:</strong> {{ $estadisticas['ultimo_consejo']->titulo }}<br>
                                <small class="text-muted">
                                    Publicado: {{ $estadisticas['ultimo_consejo']->fecha_creacion->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-comment-medical text-info"></i>
                                <p>Comparte tu conocimiento con los pacientes.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="action-card-footer">
                        <a href="#" class="btn-action-primary">
                            Ver Todos
                        </a>
                        <small class="text-muted">
                            {{ $estadisticas['consejos_publicados'] }} consejos totales
                        </small>
                    </div>
                </div>

                <!-- Crear Nuevo Consejo -->
                <div class="action-card">
                    <div class="action-card-header">
                        <h3 class="action-card-title">Crear nuevo consejo</h3>
                        <p class="action-card-subtitle">
                            Redacta una nueva recomendación para los pacientes.
                        </p>
                    </div>
                    
                    <div class="action-card-content">
                        <div class="text-center py-3">
                            <i class="fas fa-pen-nib fa-3x text-primary mb-3"></i>
                            <p class="text-muted">
                                Comparte tu experiencia profesional con recomendaciones útiles 
                                para mejorar el bienestar de los pacientes.
                            </p>
                        </div>
                    </div>
                    
                    <div class="action-card-footer">
                        <a href="#" class="btn-action-primary">
                            Crear Consejo
                        </a>
                        <small class="text-muted">
                            {{ $estadisticas['consejos_este_mes'] }} este mes
                        </small>
                    </div>
                </div>

                <!-- Perfil Profesional -->
                <div class="action-card">
                    <div class="action-card-header">
                        <h3 class="action-card-title">Perfil</h3>
                        <p class="action-card-subtitle">
                            Consulta tus datos profesionales registrados en el sistema.
                        </p>
                    </div>
                    
                    <div class="action-card-content">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="stat-number-large">{{ $estadisticas['consejos_publicados'] }}</div>
                                    <div class="stat-label-large">Consejos</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="stat-number-large">{{ $estadisticas['pacientes_activos'] }}</div>
                                    <div class="stat-label-large">Pacientes Activos</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="action-card-footer">
                        <a href="#" class="btn-action-primary">
                            Ver Perfil
                        </a>
                        <small class="text-muted">
                            Actualizado recientemente
                        </small>
                    </div>
                </div>
            </div>

            <!-- Mis Consejos Recientes -->
            @if($misConsejos->count() > 0)
            <div class="content-section">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="fas fa-history me-2"></i>Mis Consejos Recientes
                    </h4>
                </div>
                <div class="section-body">
                    <ul class="item-list">
                        @foreach($misConsejos as $consejo)
                        <li class="item-list-item">
                            <div class="item-info">
                                <div class="item-title">{{ $consejo->titulo }}</div>
                                <div class="item-subtitle">
                                    {{ Str::limit($consejo->descripcion, 100) }}
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $consejo->fecha_creacion->diffForHumans() }}
                                </small>
                            </div>
                            <div class="item-actions">
                                <a href="#" class="btn-item-action">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="#" class="btn-item-action">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="section-footer">
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>Ver Todos los Consejos
                    </a>
                </div>
            </div>
            @endif

            <!-- Estadísticas de Impacto -->
            <div class="content-section">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="fas fa-chart-line me-2"></i>Impacto de mis Consejos
                    </h4>
                </div>
                <div class="section-body">
                    <div class="stats-grid">
                        <div class="stat-card-unified primary">
                            <div class="stat-icon-large">
                                <i class="fas fa-comment-medical"></i>
                            </div>
                            <h3 class="stat-number-large">{{ $estadisticas['consejos_publicados'] }}</h3>
                            <p class="stat-label-large">Total Publicados</p>
                        </div>
                        
                        <div class="stat-card-unified success">
                            <div class="stat-icon-large">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3 class="stat-number-large">{{ $estadisticas['consejos_este_mes'] }}</h3>
                            <p class="stat-label-large">Este Mes</p>
                        </div>
                        
                        <div class="stat-card-unified info">
                            <div class="stat-icon-large">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="stat-number-large">{{ $estadisticas['pacientes_activos'] }}</h3>
                            <p class="stat-label-large">Pacientes Activos</p>
                        </div>
                        
                        <div class="stat-card-unified warning">
                            <div class="stat-icon-large">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h3 class="stat-number-large">95</h3>
                            <p class="stat-label-large">% Satisfacción</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection