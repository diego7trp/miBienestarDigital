@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-plus me-2"></i>Nueva Rutina</h4>
                <a href="{{ route('rutinas.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('rutinas.store') }}">
                    @csrf
                    
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
                                       value="{{ old('nombre') }}" 
                                       required 
                                       maxlength="50"
                                       placeholder="Ej: Ejercicio matutino, Meditación nocturna">
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
                                       value="{{ old('Horario') }}">
                                @error('Horario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Opcional</div>
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
                                  placeholder="Describe tu rutina, objetivos, pasos a seguir...">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Máximo 1000 caracteres (opcional)</div>
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
                                    <option value="">Seleccionar frecuencia...</option>
                                    <option value="Diaria" {{ old('Frecuencia') == 'Diaria' ? 'selected' : '' }}>Diaria</option>
                                    <option value="Lunes a Viernes" {{ old('Frecuencia') == 'Lunes a Viernes' ? 'selected' : '' }}>Lunes a Viernes</option>
                                    <option value="Fines de Semana" {{ old('Frecuencia') == 'Fines de Semana' ? 'selected' : '' }}>Fines de Semana</option>
                                    <option value="Semanal" {{ old('Frecuencia') == 'Semanal' ? 'selected' : '' }}>Semanal</option>
                                    <option value="Quincenal" {{ old('Frecuencia') == 'Quincenal' ? 'selected' : '' }}>Quincenal</option>
                                    <option value="Mensual" {{ old('Frecuencia') == 'Mensual' ? 'selected' : '' }}>Mensual</option>
                                    <option value="Personalizada" {{ old('Frecuencia') == 'Personalizada' ? 'selected' : '' }}>Personalizada</option>
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
                                                {{ old('id_habito') == $habito->id_habito ? 'selected' : '' }}>
                                            {{ $habito->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_habito')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Relaciona esta rutina con un hábito existente</div>
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
                                   {{ old('notificaciones') ? 'checked' : '' }}>
                            <label class="form-check-label" for="notificaciones">
                                <i class="fas fa-bell me-2"></i>Activar notificaciones
                            </label>
                        </div>
                        <div class="form-text">Recibirás recordatorios según el horario configurado</div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('rutinas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Crear Rutina
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Tips para crear rutinas -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips para crear rutinas efectivas</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Sé específico:</strong> Define claramente qué vas a hacer y cuándo
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Empieza pequeño:</strong> Mejor 10 minutos diarios que 2 horas una vez por semana
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Usa horarios fijos:</strong> Ayuda a crear el hábito automáticamente
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Conecta con hábitos:</strong> Relaciona la rutina con un mal hábito que quieres cambiar
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nombreInput = document.getElementById('nombre');
    const descripcionTextarea = document.getElementById('descripcion');
    
    // Agregar contador visual
    function agregarContador(elemento, maximo) {
        const contador = document.createElement('div');
        contador.className = 'form-text text-end';
        contador.style.fontSize = '0.8rem';
        
        function actualizar() {
            const actual = elemento.value.length;
            contador.textContent = `${actual}/${maximo}`;
            
            if (actual > maximo * 0.9) {
                contador.className = 'form-text text-end text-warning';
            } else if (actual > maximo * 0.8) {
                contador.className = 'form-text text-end text-info';
            } else {
                contador.className = 'form-text text-end text-muted';
            }
        }
        
        elemento.addEventListener('input', actualizar);
        elemento.parentNode.appendChild(contador);
        actualizar();
    }
    
    agregarContador(nombreInput, 50);
    agregarContador(descripcionTextarea, 1000);
    
    // Auto-activar notificaciones si se selecciona horario
    const horarioInput = document.getElementById('Horario');
    const notificacionesCheck = document.getElementById('notificaciones');
    
    horarioInput.addEventListener('change', function() {
        if (this.value && !notificacionesCheck.checked) {
            notificacionesCheck.checked = true;
        }
    });
});
</script>
@endsection