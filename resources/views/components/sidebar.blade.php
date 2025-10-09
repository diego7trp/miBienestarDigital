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
        @endif
        <a href="{{ route('metas.index') }}" class="nav-link-custom {{ request()->routeIs('metas.*') ? 'active' : '' }}">
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