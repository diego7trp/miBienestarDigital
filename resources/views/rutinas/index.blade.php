@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <x-sidebar />
    
    <div class="main-content">
<div class="routines-header">
    <h2 class="routines-title"><i class="fas fa-tasks me-2"></i>Mis Rutinas</h2>
    <a href="{{ route('rutinas.create') }}" class="btn-new-routine">
        <i class="fas fa-plus me-2"></i>Nueva Rutina
    </a>
</div>

@if($rutinas->count() > 0)
<div class="row">
    @foreach($rutinas as $rutina)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="routine-card">
            <div class="routine-card-header">
                <h5 class="routine-name">
                    <i class="fas fa-bookmark me-2 text-primary"></i>{{ Str::limit($rutina->nombre, 20) }}
                </h5>
                @php
                    $validacionHoy = $rutina->validaciones->where('fecha', now()->format('Y-m-d'))->first();
                    $completada = $validacionHoy && $validacionHoy->completada;
                @endphp
                <button class="status-btn {{ $completada ? 'completed' : 'pending' }} btn-toggle-rutina" 
                        data-rutina-id="{{ $rutina->id_rutina }}"
                        title="{{ $completada ? 'Completada hoy' : 'Marcar como completada' }}">
                    {{ $completada ? '✓' : '○' }}
                </button>
            </div>
            
            <div class="routine-card-body">
                <p class="routine-description">
                    {{ Str::limit($rutina->descripcion ?: 'Sin descripción disponible', 80) }}
                </p>
                
                <div class="routine-meta">
                    <small class="routine-meta-item">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <strong>Frecuencia:</strong> {{ $rutina->Frecuencia }}
                    </small>
                    
                    @if($rutina->Horario)
                        <small class="routine-meta-item">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Horario:</strong> {{ $rutina->Horario->format('H:i') }}
                        </small>
                    @endif
                    
                    @if($rutina->habito)
                        <small class="routine-meta-item">
                            <i class="fas fa-tag me-2"></i>
                            <strong>Hábito:</strong> {{ $rutina->habito->nombre }}
                        </small>
                    @endif
                    
                    @if($rutina->notificaciones)
                        <small class="routine-meta-item text-success">
                            <i class="fas fa-bell me-2"></i>Notificaciones activas
                        </small>
                    @endif
                </div>
                
                <!-- Progreso -->
                @php
                    $totalValidaciones = $rutina->validaciones->count();
                    $completadas = $rutina->validaciones->where('completada', true)->count();
                    $porcentaje = $totalValidaciones > 0 ? ($completadas / $totalValidaciones) * 100 : 0;
                @endphp
                
                @if($totalValidaciones > 0)
                    <div class="routine-progress">
                        <div class="progress-info">
                            <span class="progress-label">Progreso</span>
                            <span class="progress-percentage">{{ number_format($porcentaje, 0) }}%</span>
                        </div>
                        <div class="progress-bar-custom">
                            <div class="progress-fill" style="width: {{ $porcentaje }}%"></div>
                        </div>
                        <div class="progress-text">{{ $completadas }} de {{ $totalValidaciones }} días</div>
                    </div>
                @endif
            </div>
            
            <div class="routine-card-footer">
                <div class="btn-group-custom">
                    <a href="{{ route('rutinas.show', $rutina->id_rutina) }}" 
                       class="btn-action btn-view"
                       title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('rutinas.edit', $rutina->id_rutina) }}" 
                       class="btn-action btn-edit"
                       title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" 
                            class="btn-action btn-delete" 
                            data-rutina-id="{{ $rutina->id_rutina }}"
                            data-rutina-nombre="{{ $rutina->nombre }}"
                            title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Paginación -->
<div class="d-flex justify-content-center mt-4">
    {{ $rutinas->links() }}
</div>

@else
<div class="empty-state">
    <i class="fas fa-tasks"></i>
    <h4>No tienes rutinas creadas</h4>
    <p>Crea tu primera rutina para empezar a mejorar tus hábitos diarios</p>
    <a href="{{ route('rutinas.create') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-plus me-2"></i>Crear Mi Primera Rutina
    </a>
</div>
@endif

<!-- Modal de confirmación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que quieres eliminar la rutina <strong id="rutina-nombre"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Esta acción no se puede deshacer y se eliminará todo el historial.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Eliminar rutina con modal
    $('.btn-delete').click(function() {
        const rutinaId = $(this).data('rutina-id');
        const rutinaNombre = $(this).data('rutina-nombre');
        
        $('#rutina-nombre').text(rutinaNombre);
        $('#delete-form').attr('action', `/rutinas/${rutinaId}`);
        $('#deleteModal').modal('show');
    });
    
    // Error visual intencional: botones desalineados
    setTimeout(() => {
        $('.btn-action:nth-child(2)').each(function() {
            $(this).css('margin-top', '2px');
        });
    }, 1000);
});
</script>
    </div>
</div>
@endsection