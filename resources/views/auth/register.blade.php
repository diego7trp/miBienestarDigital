@extends('layouts.auth')

@section('title', 'Formulario de Registro')
@section('subtitle', 'Crea tu cuenta y personaliza tu experiencia')

@section('content')
<form method="POST" action="{{ route('register') }}" id="registerForm">
    @csrf
    
    <!-- Información Básica -->
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="nombre" class="form-label">
                    <i class="fas fa-user me-2"></i>Nombre Completo *
                </label>
                <input type="text" 
                       class="form-control @error('nombre') is-invalid @enderror" 
                       id="nombre" 
                       name="nombre" 
                       value="{{ old('nombre') }}" 
                       required>
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="genero" class="form-label">
                    <i class="fas fa-venus-mars me-2"></i>Género
                </label>
                <select class="form-select @error('genero') is-invalid @enderror" 
                        id="genero" name="genero">
                    <option value="">Seleccionar......</option>
                    <option value="masculino" {{ old('genero') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="femenino" {{ old('genero') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                </select>
                @error('genero')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="edad" class="form-label">
                    <i class="fas fa-birthday-cake me-2"></i>Edad
                </label>
                <input type="number" 
                       class="form-control @error('edad') is-invalid @enderror" 
                       id="edad" 
                       name="edad" 
                       value="{{ old('edad') }}"
                       min="13" 
                       max="120"
                       placeholder="años">
                @error('edad')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="mb-3">
                <label for="peso" class="form-label">
                    <i class="fas fa-weight me-2"></i>Peso (kg)
                </label>
                <input type="number" 
                       class="form-control @error('peso') is-invalid @enderror" 
                       id="peso" 
                       name="peso" 
                       value="{{ old('peso') }}"
                       min="20" 
                       max="500"
                       step="0.1"
                       placeholder="kg">
                @error('peso')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="mb-3">
                <label for="altura" class="form-label">
                    <i class="fas fa-ruler-vertical me-2"></i>Altura (cm)
                </label>
                <input type="number" 
                       class="form-control @error('altura') is-invalid @enderror" 
                       id="altura" 
                       name="altura" 
                       value="{{ old('altura') }}"
                       min="100" 
                       max="250"
                       placeholder="cm">
                @error('altura')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div id="imc-result" class="form-text text-muted small"></div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="telefono" class="form-label">
            <i class="fas fa-phone me-2"></i>Número Telefónico
        </label>
        <input type="tel" 
               class="form-control @error('telefono') is-invalid @enderror" 
               id="telefono" 
               name="telefono" 
               value="{{ old('telefono') }}"
               placeholder="+57 300 123 4567">
        @error('telefono')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="condiciones_medicas" class="form-label">
            <i class="fas fa-heartbeat me-2"></i>Condiciones Médicas
        </label>
        <textarea class="form-control @error('condiciones_medicas') is-invalid @enderror" 
                  id="condiciones_medicas" 
                  name="condiciones_medicas" 
                  rows="3"
                  placeholder="Menciona cualquier condición médica relevante (opcional)">{{ old('condiciones_medicas') }}</textarea>
        @error('condiciones_medicas')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Cuenta -->
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="correo" class="form-label">
                    <i class="fas fa-envelope me-2"></i>Correo Electrónico *
                </label>
                <input type="email" 
                       class="form-control @error('correo') is-invalid @enderror" 
                       id="correo" 
                       name="correo" 
                       value="{{ old('correo') }}" 
                       required>
                @error('correo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-2"></i>Contraseña *
                </label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label">
            <i class="fas fa-lock me-2"></i>Confirmar Contraseña *
        </label>
        <input type="password" 
               class="form-control" 
               id="password_confirmation" 
               name="password_confirmation" 
               required>
    </div>

    <!-- Malos Hábitos -->
    <div class="mb-4">
        <label class="form-label">
            <i class="fas fa-exclamation-triangle me-2"></i>Selecciona los hábitos que deseas mejorar:
        </label>
        <div class="row">
            @foreach($habitosDisponibles as $habito)
            <div class="col-md-6 col-lg-4">
                <div class="form-check mb-2">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="habito_{{ $loop->index }}" 
                           name="malos_habitos[]" 
                           value="{{ $habito }}"
                           {{ in_array($habito, old('malos_habitos', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="habito_{{ $loop->index }}">
                        {{ $habito }}
                    </label>
                </div>
            </div>
            @endforeach
        </div>
        <small class="text-muted">Esto nos ayudará a personalizar tu experiencia y crear rutinas específicas para ti.</small>
    </div>

    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-user-plus me-2"></i>Registrarme
        </button>
    </div>

    <div class="text-center">
        <p class="text-muted">¿Ya tienes una cuenta?</p>
        <a href="{{ route('login') }}" class="text-decoration-none">
            <i class="fas fa-sign-in-alt me-2"></i>Inicia sesión
        </a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calcular IMC automáticamente
    const pesoInput = document.getElementById('peso');
    const alturaInput = document.getElementById('altura');
    const imcResult = document.getElementById('imc-result');
    
    function calcularIMC() {
        const peso = parseFloat(pesoInput.value);
        const altura = parseFloat(alturaInput.value) / 100; // convertir cm a m
        
        if (peso && altura && peso > 0 && altura > 0) {
            const imc = peso / (altura * altura);
            let categoria = '';
            let color = '';
            
            if (imc < 18.5) {
                categoria = 'Bajo peso';
                color = 'text-info';
            } else if (imc < 25) {
                categoria = 'Peso normal';
                color = 'text-success';
            } else if (imc < 30) {
                categoria = 'Sobrepeso';
                color = 'text-warning';
            } else {
                categoria = 'Obesidad';
                color = 'text-danger';
            }
            
            imcResult.innerHTML = `IMC: ${imc.toFixed(1)} - <span class="${color}">${categoria}</span>`;
        } else {
            imcResult.innerHTML = '';
        }
    }
    
    pesoInput.addEventListener('input', calcularIMC);
    alturaInput.addEventListener('input', calcularIMC);
});
</script>
@endsection