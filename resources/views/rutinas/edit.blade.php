@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-edit me-2"></i>Editar Rutina</h4>
                <a href="{{ route('rutinas.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('rutinas.update', $rutina->id_rutina) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-tag me-2"></i>Nombre de la Rutina *
                                </label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre', $rutina->nombre) }}" 
                                       required 
                                       maxlength="50"
                                       placeholder="Ej: Ejercicio matutino">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Máximo 50 caracteres</div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="Horario" class="form-label">
                                    <i class="fas fa-clock me-2"></i>Horario
                                </label>
                                <input type="time" 
                                       class="form-control @error('Horario') is-invalid @enderror" 
                                       id="Horario" 
                                       name="Horario" 
                                       value="{{ old('Horario', $rutina->Horario ? $rutina->Horario->format('H:i') : '') }}">
                                @error('Horario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">
                            <i class="fas fa-align-left me-2"></i>Descripción
                        </label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" 
                                  name="descripcion" 
                                  rows="4"
                                  maxlength="1000"
                                  placeholder="Describe tu rutina...">{{ old('descripcion', $rutina->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Máximo 1000 caracteres</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Frecuencia" class="form-label">
                                    <i class="fas fa-calendar-alt me-2"></i>Frecuencia *
                                </label>
                                <select class="form-select @error('Frecuencia') is-invalid @enderror" 
                                        id="Frecuencia" 
                                        name="Frecuencia" 
                                        required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Diaria" {{ old('Frecuencia', $rutina->Frecuencia) == 'Diaria' ? 'selected' : '' }}>Diaria</option>
                                    <option value="Lunes a Viernes" {{ old('Frecuencia', $rutina->Frecuencia) == 'Lunes a Viernes' ? 'selected' : '' }}>Lunes a Viernes</option>
                                    <option value="Fines de Semana" {{ old('Frecuencia', $rutina->Frecuencia) == 'Fines de Semana' ? 'selected' : '' }}>Fines de Semana</option>
                                    <option value="Semanal" {{ old('Frecuencia', $rutina->Frecuencia) == 'Semanal' ? 'selected' : '' }}>Semanal</option>
                                    <option value="Quincenal" {{ old('Frecuencia', $rutina->Frecuencia) == 'Quincenal' ? 'selected' : '' }}>Quincenal</option>
                                    <option value="Mensual" {{ old('Frecuencia', $rutina->Frecuencia) == 'Mensual' ? 'selected' : '' }}>Mensual</option>
                                    <option value="Personalizada" {{ old('Frecuencia', $rutina->Frecuencia) == 'Personalizada' ? 'selected' : '' }}>Personalizada</option>
                                </select>
                                @error('Frecuencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_habito" class="form-label">
                                    <i class="fas fa-bullseye me-2"></i>Hábito Relacionado
                                </label>
                                <select class="form-select @error('id_habito') is-invalid @enderror" 
                                        id="id_habito" 
                                        name="id_habito">
                                    <option value="">Sin hábito específico</option>
                                    @foreach($habitos as $habito)
                                        <option value="{{ $habito->id_habito }}" 
                                                {{ old('id_habito', $rutina->id_habito) == $habito->id_habito ? 'selected' : '' }}>
                                            {{ $habito->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_habito')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="notificaciones" 
                                   name="notificaciones" 
                                   value="1" 
                                   {{ old('notificaciones', $rutina->notificaciones) ? 'checked' : '' }}>
                            <label class="form-check-label" for="notificaciones">
                                <i class="fas fa-bell me-2"></i>Activar notificaciones
                            </label>
                        </div>
                        <div class="form-text">Recibirás recordatorios según el horario configurado</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Rutina
                            </button>
                            <a href="{{ route('rutinas.index') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                        <div>
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    onclick="confirmarEliminacion()">
                                <i class="fas fa-trash me-2"></i>Eliminar Rutina
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Formulario oculto para eliminar -->
                <form id="delete-form" 
                      method="POST" 
                      action="{{ route('rutinas.destroy', $rutina->id_rutina) }}" 
                      style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion() {
    if (confirm('⚠️ ¿Estás seguro de que quieres eliminar esta rutina?\n\nEsta acción no se puede deshacer y se eliminará todo el historial de la rutina.')) {
        document.getElementById('delete-form').submit();
    }
}

// Contador de caracteres
document.addEventListener('DOMContentLoaded', function() {
    const descripcionTextarea = document.getElementById('descripcion');
    const nombreInput = document.getElementById('nombre');
    
    // Agregar contador visual si es necesario
    function agregarContador(elemento, maximo) {
        const contador = document.createElement('div');
        contador.className = 'form-text text-end';
        contador.style.fontSize = '0.8rem';
        
        function actualizar() {
            const actual = elemento.value.length;
            contador.textContent = `${actual}/${maximo}`;
            contador.className = actual > maximo * 0.9 ? 'form-text text-end text-warning' : 'form-text text-end text-muted';
        }
        
        elemento.addEventListener('input', actualizar);
        elemento.parentNode.appendChild(contador);
        actualizar();
    }
    
    agregarContador(nombreInput, 50);
    agregarContador(descripcionTextarea, 1000);
});
</script>
@endsection