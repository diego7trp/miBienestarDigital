@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-bookmark me-2 text-primary"></i>{{ $rutina->nombre }}</h4>
                <div class="btn-group">
                    <a href="{{ route('rutinas.edit', $rutina->id_rutina) }}" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('rutinas.index') }}" 
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Información básica -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-info-circle me-2"></i>Información General</h6>
                        <ul class="list-unstyled">
                            <li><strong>Frecuencia:</strong> {{ $rutina->Frecuencia }}</li>
                            @if($rutina->Horario)
                                <li><strong>Horario:</strong> {{ $rutina->Horario->format('H:i') }}</li>
                            @endif
                            @if($rutina->habito)
                                <li><strong>Hábito relacionado:</strong> {{ $rutina->habito->nombre }}</li>
                            @endif
                            <li><strong>Notificaciones:</strong> 
                                @if($rutina->notificaciones)
                                    <span class="badge bg-success">Activas</span>
                                @else
                                    <span class="badge bg-secondary">Inactivas</span>
                                @endif
                            </li>
                            <li><strong>Creada:</strong> {{ $rutina->fecha_creacion->format('d/m/Y H:i') }}</li>
                        </ul>
                    </div>
                    
                    <div class="col-md-6">
                        <h6><i class="fas fa-chart-bar me-2"></i>Estadísticas</h6>
                        <ul class="list-unstyled">
                            <li><strong>Total de días:</strong> {{ $estadisticas['total_dias'] }}</li>
                            <li><strong>Días completados:</strong> 
                                <span class="text-success">{{ $estadisticas['dias_completados'] }}</span>
                            </li>
                            <li><strong>Días fallados:</strong> 
                                <span class="text-danger">{{ $estadisticas['dias_fallados'] }}</span>
                            </li>
                            <li><strong>Racha actual:</strong> 
                                <span class="badge bg-warning">{{ $estadisticas['racha_actual'] }} días</span>
                            </li>
                            @if($estadisticas['ultima_actividad'])
                                <li><strong>Última actividad:</strong> 
                                    {{ $estadisticas['ultima_actividad']->fecha->format('d/m/Y') }}
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                
                <!-- Descripción -->
                @if($rutina->descripcion)
                    <div class="mb-4">
                        <h6><i class="fas fa-align-left me-2"></i>Descripción</h6>
                        <p class="text-muted">{{ $rutina->descripcion }}</p>
                    </div>
                @endif
                
                <!-- Progreso visual -->
                @if($estadisticas['total_dias'] > 0)
                    @php
                        $porcentaje = ($estadisticas['dias_completados'] / $estadisticas['total_dias']) * 100;
                    @endphp
                    <div class="mb-4">
                        <h6><i class="fas fa-chart-line me-2"></i>Progreso General</h6>
                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ $porcentaje }}%" 
                                 aria-valuenow="{{ $porcentaje }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ number_format($porcentaje, 1) }}%
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ $estadisticas['dias_completados'] }} completados de {{ $estadisticas['total_dias'] }} días totales
                        </small>
                    </div>
                @endif
                
                <!-- Acción rápida para hoy -->
                @php
                    $validacionHoy = $rutina->validaciones->where('fecha', now()->format('Y-m-d'))->first();
                    $completadaHoy = $validacionHoy && $validacionHoy->completada;
                @endphp
                
                <div class="alert {{ $completadaHoy ? 'alert-success' : 'alert-info' }} d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas {{ $completadaHoy ? 'fa-check-circle' : 'fa-clock' }} me-2"></i>
                        <strong>Hoy ({{ now()->format('d/m/Y') }}):</strong>
                        {{ $completadaHoy ? 'Rutina completada ✓' : 'Rutina pendiente' }}
                    </div>
                    <button class="btn btn-sm {{ $completadaHoy ? 'btn-outline-secondary' : 'btn-success' }} btn-toggle-rutina" 
                            data-rutina-id="{{ $rutina->id_rutina }}">
                        {{ $completadaHoy ? 'Desmarcar' : 'Marcar Completada' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Panel lateral -->
    <div class="col-md-4">
        <!-- Actividad reciente -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Actividad Reciente</h6>
            </div>
            <div class="card-body p-0">
                @if($rutina->validaciones->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($rutina->validaciones->take(10) as $validacion)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <span class="small">
                                    {{ $validacion->fecha->format('d/m/Y') }}
                                </span>
                                <span class="badge {{ $validacion->completada ? 'bg-success' : 'bg-danger' }}">
                                    {{ $validacion->completada ? 'Completada' : 'Fallada' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($rutina->validaciones->count() > 10)
                        <div class="card-footer text-center">
                            <small class="text-muted">
                                Mostrando las 10 actividades más recientes
                            </small>
                        </div>
                    @endif
                @else
                    <div class="card-body text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                        <small>No hay actividad registrada aún</small>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Acciones rápidas -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('rutinas.edit', $rutina->id_rutina) }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Editar Rutina
                    </a>
                    
                    <button class="btn btn-outline-success btn-toggle-rutina" 
                            data-rutina-id="{{ $rutina->id_rutina }}">
                        <i class="fas {{ $completadaHoy ? 'fa-undo' : 'fa-check' }} me-2"></i>
                        {{ $completadaHoy ? 'Desmarcar Hoy' : 'Completar Hoy' }}
                    </button>
                    
                    <a href="{{ route('rutinas.create') }}" 
                       class="btn btn-outline-info">
                        <i class="fas fa-plus me-2"></i>Nueva Rutina Similar
                    </a>
                    
                    <button type="button" 
                            class="btn btn-outline-danger" 
                            onclick="confirmarEliminacion()">
                        <i class="fas fa-trash me-2"></i>Eliminar Rutina
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formulario oculto para eliminar -->
<form id="delete-form" 
      method="POST" 
      action="{{ route('rutinas.destroy', $rutina->id_rutina) }}" 
      style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
// Marcar/desmarcar rutina como completada
$('.btn-toggle-rutina').click(function() {
    const rutinaId = $(this).data('rutina-id');
    const button = $(this);
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.post(`/rutinas/${rutinaId}/completar`, function(data) {
        if(data.success) {
            // Recargar la página para actualizar todas las estadísticas
            location.reload();
        }
    }).fail(function() {
        alert('Error al marcar la rutina. Intenta de nuevo.');
    });
});

function confirmarEliminacion() {
    if (confirm('⚠️ ¿Estás seguro de que quieres eliminar esta rutina?\n\nEsta acción no se puede deshacer y se eliminará todo el historial de la rutina.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection