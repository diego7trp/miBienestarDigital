@extends('layouts.app')

@section('content')
<!-- Header del Dashboard -->
<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard</h2>
            <p class="date mb-0">{{ now()->format('l, d \d\e F \d\e Y') }}</p>
        </div>
        <div class="text-end">
            <i class="fas fa-calendar fa-2x text-primary"></i>
        </div>
    </div>
</div>

<!-- Bienvenida -->
<div class="alert alert-info border-inconsistent mb-4">
    <h4><i class="fas fa-hand-wave me-2"></i>¡Hola, {{ Auth::user()->nombre }}!</h4>
    <p class="mb-0 wrong-font-size">Bienvenido a tu panel de bienestar digital. Aquí puedes ver el resumen de tus rutinas y actividades.</p>
</div>

<!-- Cards de Resumen -->
<div class="row mb-4 error-spacing">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card primary">
            <div class="icon">
                <i class="fas fa-tasks"></i>
            </div>
            <h3>{{ Auth::user()->rutinas->count() }}</h3>
            <p>Rutinas Totales</p>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card success">
            <div class="icon">
                <i class="fas fa-heartbeat"></i>
            </div>
            <h3>{{ Auth::user()->habitos->count() }}</h3>
            <p>Hábitos</p>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card accent">
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <h3>{{ Auth::user()->tareas->where('estado', 'pendiente')->count() }}</h3>
            <p>Tareas Pendientes</p>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card secondary">
            <div class="icon">
                <i class="fas fa-trophy"></i>
            </div>
            <h3>{{ Auth::user()->metas->where('cumplida', true)->count() }}</h3>
            <p>Metas Cumplidas</p>
        </div>
    </div>
</div>

<!-- Rutinas de Hoy -->
<div class="row">
    <div class="col-md-8">
        <div class="pending-routines">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Rutinas de Hoy</h5>
                    <a href="{{ route('rutinas.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-2"></i>Nueva
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @php
                    $rutinasPendientes = Auth::user()->rutinas()
                        ->whereDoesntHave('validaciones', function($query) {
                            $query->where('fecha', today())->where('completada', true);
                        })
                        ->orderBy('Horario')
                        ->get();
                @endphp
                
                @if($rutinasPendientes->count() > 0)
                    @foreach($rutinasPendientes as $rutina)
                        <div class="routine-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1 misaligned">
                                    <h6 class="routine-title">{{ $rutina->nombre }}</h6>
                                    <p class="routine-description">{{ Str::limit($rutina->descripcion ?: 'Sin descripción', 60) }}</p>
                                    <div class="routine-meta">
                                        @if($rutina->Horario)
                                            <i class="fas fa-clock me-1"></i>{{ $rutina->Horario->format('H:i') }}
                                        @endif
                                        @if($rutina->habito)
                                            <i class="fas fa-tag ms-2 me-1"></i>{{ $rutina->habito->nombre }}
                                        @endif
                                    </div>
                                </div>
                                <div class="routine-actions">
                                    <button class="status-btn pending btn-toggle-rutina" 
                                            data-rutina-id="{{ $rutina->id_rutina }}"
                                            title="Marcar como completada">
                                        ○
                                    </button>
                                    <a href="{{ route('rutinas.show', $rutina) }}" 
                                       class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h5>¡Excelente trabajo!</h5>
                        <p class="text-muted">Has completado todas tus rutinas de hoy</p>
                        <a href="{{ route('rutinas.index') }}" class="btn btn-primary">Ver Todas las Rutinas</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Panel Lateral -->
    <div class="col-md-4">
        <!-- Progreso Semanal -->
        <div class="sidebar-panel mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Progreso Hoy</h6>
            </div>
            <div class="card-body text-center">
                @php
                    $totalRutinas = Auth::user()->rutinas->count();
                    $rutinasCompletadas = Auth::user()->rutinas()
                        ->whereHas('validaciones', function($query) {
                            $query->where('fecha', today())->where('completada', true);
                        })
                        ->count();
                    $porcentaje = $totalRutinas > 0 ? ($rutinasCompletadas / $totalRutinas) * 100 : 0;
                @endphp
                
                <div class="progress-circle" style="background: conic-gradient(var(--success-color) {{ $porcentaje * 3.6 }}deg, var(--border-light) {{ $porcentaje * 3.6 }}deg);">
                    <div class="progress-text">{{ number_format($porcentaje, 0) }}%</div>
                </div>
                
                <h5>{{ number_format($porcentaje, 1) }}%</h5>
                <p class="text-muted mb-0">{{ $rutinasCompletadas }} de {{ $totalRutinas }} rutinas completadas</p>
            </div>
        </div>
        
        <!-- Acciones Rápidas -->
        <div class="sidebar-panel">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('rutinas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nueva Rutina
                    </a>
                    <a href="{{ route('rutinas.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>Ver Todas
                    </a>
                    <button class="btn btn-outline-info" onclick="location.reload()">
                        <i class="fas fa-sync me-2"></i>Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Actualizar progreso en tiempo real
function updateDashboardProgress() {
    const circle = document.querySelector('.progress-circle');
    const text = document.querySelector('.progress-text');
    
    if (circle && text) {
        const currentPercentage = parseInt(text.textContent);
        circle.style.background = `conic-gradient(var(--success-color) ${currentPercentage * 3.6}deg, var(--border-light) ${currentPercentage * 3.6}deg)`;
    }
}

// Llamar al actualizar rutinas
document.addEventListener('DOMContentLoaded', function() {
    updateDashboardProgress();
});
</script>
@endsection