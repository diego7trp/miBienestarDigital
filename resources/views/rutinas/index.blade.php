@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tasks me-2"></i>Mis Rutinas</h2>
    <a href="{{ route('rutinas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nueva Rutina
    </a>
</div>

@if($rutinas->count() > 0)
<div class="row">
    @foreach($rutinas as $rutina)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-bookmark me-2 text-primary"></i>{{ Str::limit($rutina->nombre, 20) }}
                </h5>
                @php
                    $validacionHoy = $rutina->validaciones->where('fecha', now()->format('Y-m-d'))->first();
                    $completada = $validacionHoy && $validacionHoy->completada;
                @endphp
                <button class="btn btn-sm {{ $completada ? 'btn-success' : 'btn-outline-secondary' }} btn-toggle-rutina" 
                        data-rutina-id="{{ $rutina->id_rutina }}"
                        title="{{ $completada ? 'Completada hoy' : 'Marcar como completada' }}">
                    {{ $completada ? '✓' : '○' }}
                </button>
            </div>
            
            <div class="card-body d-flex flex-column">
                <p class="card-text text-muted small flex-grow-1">
                    {{ Str::limit($rutina->descripcion ?: 'Sin descripción', 80) }}
                </p>
                
                <div class="mb-3">
                    <small class="text-muted d-block">
                        <i class="fas fa-calendar-alt me-1"></i>
                        <strong>Frecuencia:</strong> {{ $rutina->Frecuencia }}
                    </small>
                    
                    @if($rutina->Horario)
                        <small class="text-muted d-block">
                            <i class="fas fa-clock me-1"></i>
                            <strong>Horario:</strong> {{ $rutina->Horario->format('H:i') }}
                        </small>
                    @endif
                    
                    @if($rutina->habito)
                        <small class="text-muted d-block">
                            <i class="fas fa-tag me-1"></i>
                            <strong>Hábito:</strong> {{ $rutina->habito->nombre }}
                        </small>
                    @endif
                    
                    @if($rutina->notificaciones)
                        <small class="text-success d-block">
                            <i class="fas fa-bell me-1"></i>Notificaciones activas
                        </small>
                    @endif
                </div>
                
                <!-- Estadísticas rápidas -->
                @php
                    $totalValidaciones = $rutina->validaciones->count();
                    $completadas = $rutina->validaciones->where('completada', true)->count();
                    $porcentaje = $totalValidaciones > 0 ? ($completadas / $totalValidaciones) * 100 : 0;
                @endphp
                
                @if($totalValidaciones > 0)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">Progreso</small>
                            <small class="text-muted">{{ number_format($porcentaje, 0) }}%</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" 
                                 role="progressbar" 
                                 style="width: {{ $porcentaje }}%" 
                                 aria-valuenow="{{ $porcentaje }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <small class="text-muted">{{ $completadas }} de {{ $totalValidaciones }} días</small>
                    </div>
                @endif
            </div>
            
            <div class="card-footer bg-transparent">
                <div class="btn-group w-100" role="group">
                    <a href="{{ route('rutinas.show', $rutina->id_rutina) }}" 
                       class="btn btn-outline-primary btn-sm"
                       title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('rutinas.edit', $rutina->id_rutina) }}" 
                       class="btn btn-outline-secondary btn-sm"
                       title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" 
                            class="btn btn-outline-danger btn-sm btn-delete" 
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
<div class="d-flex justify-content-center">
    {{ $rutinas->links() }}
</div>

@else
<div class="text-center py-5">
    <div class="mb-4">
        <i class="fas fa-tasks fa-5x text-muted"></i>
    </div>
    <h4 class="text-muted">No tienes rutinas creadas</h4>
    <p class="text-muted">Crea tu primera rutina para empezar a mejorar tus hábitos</p>
    <a href="{{ route('rutinas.create') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-plus me-2"></i>Crear Mi Primera Rutina
    </a>
</div>
@endif

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que quieres eliminar la rutina <strong id="rutina-nombre"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Esta acción no se puede deshacer y se eliminará todo el historial de la rutina.
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
                        <i class="fas fa-trash me-2"></i>Sí, Eliminar
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
    // Marcar rutina como completada
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
                button.toggleClass('btn-success btn-outline-secondary');
                button.text(data.completada ? '✓' : '○');
                button.attr('title', data.completada ? 'Completada hoy' : 'Marcar como completada');
                
                // Mostrar notificación toast
                showToast(data.mensaje, data.completada ? 'success' : 'info');
            }
        }).fail(function() {
            showToast('Error al marcar la rutina. Intenta de nuevo.', 'error');
        });
    });
    
    // Eliminar rutina
    $('.btn-delete').click(function() {
        const rutinaId = $(this).data('rutina-id');
        const rutinaNombre = $(this).data('rutina-nombre');
        
        $('#rutina-nombre').text(rutinaNombre);
        $('#delete-form').attr('action', `/rutinas/${rutinaId}`);
        $('#deleteModal').modal('show');
    });
});

// Función para mostrar toasts
function showToast(mensaje, tipo = 'info') {
    const toast = `
        <div class="toast align-items-center text-white bg-${tipo === 'success' ? 'success' : tipo === 'error' ? 'danger' : 'info'} border-0" 
             role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${mensaje}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    // Crear contenedor si no existe
    if (!document.getElementById('toast-container')) {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }
    
    const container = document.getElementById('toast-container');
    container.insertAdjacentHTML('beforeend', toast);
    
    const toastElement = container.lastElementChild;
    const bsToast = new bootstrap.Toast(toastElement);
    bsToast.show();
    
    // Remover del DOM después de que se oculte
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}
</script>
@endsection