@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
            <div class="text-muted">
                <i class="fas fa-calendar me-2"></i>{{ now()->format('d/m/Y') }}
            </div>
        </div>
    </div>
</div>

<!-- Bienvenida -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary">
            <h4><i class="fas fa-hand-wave me-2"></i>¡Hola, {{ Auth::user()->nombre }}!</h4>
            <p class="mb-0">Bienvenido a tu panel de bienestar digital. Aquí puedes ver el resumen de tus rutinas y actividades.</p>
        </div>
    </div>
</div>

<!-- Cards de Resumen -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3>{{ Auth::user()->rutinas->count() }}</h3>
                        <p class="mb-0">Rutinas Totales</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tasks fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3>{{ Auth::user()->habitos->count() }}</h3>
                        <p class="mb-0">Hábitos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-heartbeat fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3>{{ Auth::user()->tareas->where('estado', 'pendiente')->count() }}</h3>
                        <p class="mb-0">Tareas Pendientes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3>{{ Auth::user()->metas->where('cumplida', true)->count() }}</h3>
                        <p class="mb-0">Metas Cumplidas</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-trophy fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rutinas de Hoy -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Rutinas de Hoy</h5>
                <a href="{{ route('rutinas.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-2"></i>Nueva Rutina
                </a>
            </div>
            <div class="card-body">
                @php
                    $rutinasPendientes = Auth::user()->rutinas()
                        ->whereDoesntHave('validaciones', function($query) {
                            $query->where('fecha', today())->where('completada', true);
                        })
                        ->orderBy('Horario')
                        ->get();
                @endphp
                
                @if($rutinasPendientes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($rutinasPendientes as $rutina)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $rutina->nombre }}</h6>
                                    <p class="mb-1 text-muted">{{ Str::limit($rutina->descripcion, 50) }}</p>
                                    <small class="text-muted">
                                        @if($rutina->Horario)
                                            <i class="fas fa-clock me-1"></i>{{ $rutina->Horario->format('H:i') }}
                                        @endif
                                        @if($rutina->habito)
                                            <i class="fas fa-tag ms-2 me-1"></i>{{ $rutina->habito->nombre }}
                                        @endif
                                    </small>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-success btn-toggle-rutina" 
                                            data-rutina-id="{{ $rutina->id_rutina }}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <a href="{{ route('rutinas.show', $rutina) }}" 
                                       class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
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
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Progreso Semanal</h6>
            </div>
            <div class="card-body">
                @php
                    $totalRutinas = Auth::user()->rutinas->count();
                    $rutinasCompletadas = Auth::user()->rutinas()
                        ->whereHas('validaciones', function($query) {
                            $query->where('fecha', today())->where('completada', true);
                        })
                        ->count();
                    $porcentaje = $totalRutinas > 0 ? ($rutinasCompletadas / $totalRutinas) * 100 : 0;
                @endphp
                
                <div class="text-center">
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $porcentaje }}%" 
                             aria-valuenow="{{ $porcentaje }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    <h4>{{ number_format($porcentaje, 1) }}%</h4>
                    <p class="text-muted mb-0">{{ $rutinasCompletadas }} de {{ $totalRutinas }} rutinas completadas hoy</p>
                </div>
            </div>
        </div>
        
        <!-- Acciones Rápidas -->
        
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
                // Remover la rutina de la lista si se completó
                if(data.completada) {
                    button.closest('.list-group-item').fadeOut('slow', function() {
                        $(this).remove();
                        
                        // Si no hay más rutinas, mostrar mensaje de éxito
                        if($('.list-group-item').length === 0) {
                            $('.card-body').html(`
                                <div class="text-center py-4">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h5>¡Excelente trabajo!</h5>
                                    <p class="text-muted">Has completado todas tus rutinas de hoy</p>
                                    <a href="{{ route('rutinas.index') }}" class="btn btn-primary">Ver Todas las Rutinas</a>
                                </div>
                            `);
                        }
                    });
                }
                
                // Mostrar notificación
                const alertClass = data.completada ? 'alert-success' : 'alert-info';
                const alert = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="fas fa-check me-2"></i>${data.mensaje}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                if($('.alert').length) {
                    $('.alert').first().replaceWith(alert);
                } else {
                    $('.container').prepend(alert);
                }
                
                // Auto-remover la alerta después de 3 segundos
                setTimeout(() => {
                    $('.alert').fadeOut();
                }, 3000);
            }
        }).fail(function() {
            alert('Error al marcar la rutina. Intenta de nuevo.');
        });
    });
});
</script>
@endsection