@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-plus me-2"></i>Nueva Tarea</h4>
                <a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tareas.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="titulo" class="form-label">
                            <i class="fas fa-heading me-2 text-primary"></i>Título de la Tarea *
                        </label>
                        <input type="text" 
                               class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" 
                               name="titulo" 
                               value="{{ old('titulo') }}" 
                               required 
                               maxlength="100"
                               placeholder="Ej: Completar reporte mensual, Llamar al médico...">
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Máximo 100 caracteres</div>
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="form-label">
                            <i class="fas fa-align-left me-2 text-info"></i>Descripción
                        </label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" 
                                  name="descripcion" 
                                  rows="4"
                                  maxlength="1000"
                                  placeholder="Describe los detalles de la tarea, pasos a seguir, recursos necesarios...">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Máximo 1000 caracteres (opcional)</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="fecha_fin" class="form-label">
                                    <i class="fas fa-calendar me-2 text-warning"></i>Fecha de Vencimiento *
                                </label>
                                <input type="date" 
                                       class="form-control @error('fecha_fin') is-invalid @enderror" 
                                       id="fecha_fin" 
                                       name="fecha_fin" 
                                       value="{{ old('fecha_fin', now()->format('Y-m-d')) }}"
                                       min="{{ now()->format('Y-m-d') }}"
                                       required>
                                @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="prioridad" class="form-label">
                                    <i class="fas fa-flag me-2 text-danger"></i>Prioridad *
                                </label>
                                <select class="form-select @error('prioridad') is-invalid @enderror" 
                                        id="prioridad" 
                                        name="prioridad" 
                                        required>
                                    <option value="">Seleccionar prioridad...</option>
                                    <option value="alta" {{ old('prioridad') == 'alta' ? 'selected' : '' }}>
                                        🔴 Alta - Urgente e importante
                                    </option>
                                    <option value="media" {{ old('prioridad') == 'media' ? 'selected' : '' }}>
                                        🟡 Media - Importante pero no urgente
                                    </option>
                                    <option value="baja" {{ old('prioridad') == 'baja' ? 'selected' : '' }}>
                                        🔵 Baja - Puede esperar
                                    </option>
                                </select>
                                @error('prioridad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Preview de la fecha -->
                    <div class="mb-4">
                        <div class="alert alert-info" id="date-preview" style="display: none;">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Información:</strong> <span id="date-info"></span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>Crear Tarea
                            </button>
                            <a href="{{ route('tareas.index') }}" class="btn btn-secondary btn-lg ms-2">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                        <div class="text-muted small align-self-end">
                            <i class="fas fa-lightbulb me-1"></i>
                            Tip: Usa fechas realistas para mejorar tu productividad
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Card de tips -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips para crear tareas efectivas</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Sé específico:</strong> "Revisar email" → "Responder 5 emails importantes"
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Usa verbos de acción:</strong> Completar, Enviar, Llamar, Revisar
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Define el resultado:</strong> ¿Qué habrás logrado al terminar?
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-clock text-info me-2"></i>
                                <strong>Tiempo estimado:</strong> Incluye cuánto tiempo te tomará
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-calendar text-warning me-2"></i>
                                <strong>Fechas realistas:</strong> Considera tu carga de trabajo actual
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-flag text-danger me-2"></i>
                                <strong>Prioriza sabiamente:</strong> No todo puede ser urgente
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha_fin');
    const datePreview = document.getElementById('date-preview');
    const dateInfo = document.getElementById('date-info');
    
    function updateDatePreview() {
        const selectedDate = new Date(fechaInput.value);
        const today = new Date();
        const diffTime = selectedDate - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (fechaInput.value) {
            let message = '';
            let alertClass = 'alert-info';
            
            if (diffDays === 0) {
                message = 'La tarea vence hoy. ¡Asegúrate de completarla!';
                alertClass = 'alert-warning';
            } else if (diffDays === 1) {
                message = 'La tarea vence mañana.';
                alertClass = 'alert-info';
            } else if (diffDays > 1 && diffDays <= 7) {
                message = `La tarea vence en ${diffDays} días (${selectedDate.toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}).`;
                alertClass = 'alert-info';
            } else if (diffDays > 7) {
                message = `La tarea vence el ${selectedDate.toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })} (en ${diffDays} días).`;
                alertClass = 'alert-success';
            }
            
            dateInfo.textContent = message;
            datePreview.className = `alert ${alertClass}`;
            datePreview.style.display = 'block';
        } else {
            datePreview.style.display = 'none';
        }
    }
    
    fechaInput.addEventListener('change', updateDatePreview);
    
    // Ejecutar al cargar si ya hay una fecha
    if (fechaInput.value) {
        updateDatePreview();
    }
    
    // Contador de caracteres
    const tituloInput = document.getElementById('titulo');
    const descripcionTextarea = document.getElementById('descripcion');
    
    function agregarContador(elemento, maximo) {
        const contador = document.createElement('div');
        contador.className = 'form-text text-end';
        contador.style.fontSize = '0.8rem';
        
        function actualizar() {
            const actual = elemento.value.length;
            contador.textContent = `${actual}/${maximo}`;
            
            if (actual > maximo * 0.9) {
                contador.className = 'form-text text-end text-danger';
            } else if (actual > maximo * 0.8) {
                contador.className = 'form-text text-end text-warning';
            } else {
                contador.className = 'form-text text-end text-muted';
            }
        }
        
        elemento.addEventListener('input', actualizar);
        elemento.parentNode.appendChild(contador);
        actualizar();
    }
    
    agregarContador(tituloInput, 100);
    agregarContador(descripcionTextarea, 1000);
});
</script>
@endsection