@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="tasks-header">
    <h2 class="tasks-title"><i class="fas fa-tasks me-2"></i>Mis Tareas</h2>
    <a href="{{ route('tareas.create') }}" class="btn-new-task">
        <i class="fas fa-plus me-2"></i>Nueva Tarea
    </a>
</div>

<!-- Estadísticas -->
<div class="row stats-row">
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="stat-card-task hoy">
            <div class="stat-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <h3 class="stat-number">{{ $estadisticas['hoy'] }}</h3>
            <p class="stat-label">Para Hoy</p>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <a href="{{ route('tareas.calendario') }}" class="text-decoration-none">
            <div class="stat-card-task total">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <p class="stat-label mb-0">Ver Calendario</p>
            </div>
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="filters-card">
    <h6 class="filters-title"><i class="fas fa-filter me-2"></i>Filtros</h6>
    <form method="GET" action="{{ route('tareas.index') }}">
        <div class="filter-group">
            <div class="filter-item">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                    <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completadas</option>
                    <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Canceladas</option>
                </select>
            </div>
            
            <div class="filter-item">
                <label for="prioridad" class="form-label">Prioridad</label>
                <select name="prioridad" id="prioridad" class="form-select">
                    <option value="">Todas las prioridades</option>
                    <option value="alta" {{ request('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="media" {{ request('prioridad') == 'media' ? 'selected' : '' }}>Media</option>
                    <option value="baja" {{ request('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                </select>
            </div>
            
            <div class="filter-item">
                <label for="fecha_inicio" class="form-label">Desde</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
            </div>
            
            <div class="filter-item">
                <label for="fecha_final" class="form-label">Hasta</label>
                <input type="date" name="fecha_final" id="fecha_final" class="form-control" value="{{ request('fecha_final') }}">
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Filtrar
                </button>
                <a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Limpiar
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Lista de Tareas -->
@if($tareas->count() > 0)
<div class="row">
    @foreach($tareas as $tarea)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="task-card {{ $tarea->es_vencida ? 'vencida' : ($tarea->es_hoy ? 'hoy' : ($tarea->es_proxima ? 'proxima' : ($tarea->estado == 'completada' ? 'completada' : ''))) }}">
            
            <!-- Badge de tiempo -->
            @if($tarea->es_vencida && $tarea->estado == 'pendiente')
                <div class="time-badge vencida">Vencida</div>
            @elseif($tarea->es_hoy && $tarea->estado == 'pendiente')
                <div class="time-badge hoy">Hoy</div>
            @elseif($tarea->es_proxima && $tarea->estado == 'pendiente')
                <div class="time-badge proxima">Próxima</div>
            @endif
            
            <div class="task-card-header">
                <div class="task-title">
                    <span>{{ Str::limit($tarea->titulo, 30) }}</span>
                    <span class="task-priority {{ $tarea->prioridad }}">
                        <i class="{{ $tarea->icono_prioridad }} me-1"></i>{{ ucfirst($tarea->prioridad) }}
                    </span>
                </div>
                
                <div class="task-date-info">
                    <i class="fas fa-calendar me-1"></i>
                    <span>{{ $tarea->fecha_formateada }}</span>
                    <span class="task-status {{ $tarea->estado }}">{{ ucfirst($tarea->estado) }}</span>
                </div>
                
                <div class="mt-2">
                    <small class="text-muted">
                        @if($tarea->estado == 'completada')
                            <i class="fas fa-check-circle text-success me-1"></i>Completada
                        @elseif($tarea->es_vencida)
                            <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                            Vencida hace {{ abs($tarea->dias_restantes) }} {{ abs($tarea->dias_restantes) == 1 ? 'día' : 'días' }}
                        @elseif($tarea->es_hoy)
                            <i class="fas fa-clock text-warning me-1"></i>Vence hoy
                        @elseif($tarea->dias_restantes > 0)
                            <i class="fas fa-hourglass-half text-info me-1"></i>
                            {{ $tarea->dias_restantes }} {{ $tarea->dias_restantes == 1 ? 'día restante' : 'días restantes' }}
                        @endif
                    </small>
                </div>
            </div>
            
            <div class="task-card-body">
                <p class="task-description">
                    {{ $tarea->descripcion ? Str::limit($tarea->descripcion, 100) : 'Sin descripción' }}
                </p>
            </div>
            
            <div class="task-card-footer">
                <div class="task-actions">
                    <div class="task-actions-left">
                        @if($tarea->estado == 'pendiente')
                            <button class="task-complete-btn" 
                                    data-tarea-id="{{ $tarea->id_tarea }}"
                                    title="Marcar como completada">
                                <i class="fas fa-check me-1"></i>Completar
                            </button>
                        @elseif($tarea->estado == 'completada')
                            <button class="task-complete-btn completed" 
                                    data-tarea-id="{{ $tarea->id_tarea }}"
                                    title="Marcar como pendiente">
                                <i class="fas fa-undo me-1"></i>Deshacer
                            </button>
                        @endif
                    </div>
                    
                    <div class="task-actions-right">
                        <a href="{{ route('tareas.show', $tarea->id_tarea) }}" 
                           class="btn-task-action btn-task-view" 
                           title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('tareas.edit', $tarea->id_tarea) }}" 
                           class="btn-task-action btn-task-edit" 
                           title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" 
                                class="btn-task-action btn-task-delete btn-delete-task" 
                                data-tarea-id="{{ $tarea->id_tarea }}"
                                data-tarea-titulo="{{ $tarea->titulo }}"
                                title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Paginación -->
<div class="d-flex justify-content-center mt-4">
    {{ $tareas->withQueryString()->links() }}
</div>

@else
<div class="empty-tasks">
    <i class="fas fa-tasks"></i>
    <h4>No hay tareas que mostrar</h4>
    <p>
        @if(request()->hasAny(['estado', 'prioridad', 'fecha_inicio', 'fecha_final']))
            No se encontraron tareas con los filtros aplicados.
        @else
            Aún no has creado ninguna tarea. ¡Comienza organizando tu día!
        @endif
    </p>
    <a href="{{ route('tareas.create') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-plus me-2"></i>Crear Mi Primera Tarea
    </a>
</div>
@endif

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que quieres eliminar la tarea <strong id="task-title"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Esta acción no se puede deshacer.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form id="delete-task-form" method="POST" style="display: inline;">
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
    // Completar/descompletar tarea
    $('.task-complete-btn').click(function() {
        const tareaId = $(this).data('tarea-id');
        const button = $(this);
        const card = button.closest('.task-card');
        
        // Animación
        card.addClass('task-completing');
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.post(`/tareas/${tareaId}/completar`, function(data) {
            if(data.success) {
                // Mostrar notificación
                showToast(data.mensaje, data.estado === 'completada' ? 'success' : 'info');
                
                // Actualizar UI
                setTimeout(() => {
                    location.reload(); // Recargar para actualizar estadísticas
                }, 1000);
            }
        }).fail(function() {
            showToast('Error al actualizar la tarea. Intenta de nuevo.', 'error');
            card.removeClass('task-completing');
        });
    });
    
    // Eliminar tarea
    $('.btn-delete-task').click(function() {
        const tareaId = $(this).data('tarea-id');
        const tareaTitulo = $(this).data('tarea-titulo');
        
        $('#task-title').text(tareaTitle);
        $('#delete-task-form').attr('action', `/tareas/${tareaId}`);
        $('#deleteTaskModal').modal('show');
    });
    
    // Auto-submit filtros en cambio
    $('#estado, #prioridad').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@endsection