@extends('layouts.app')

@section('content')
<h2>Nueva Rutina</h2>

<form method="POST" action="{{ route('rutinas.store') }}">
    @csrf
    
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre *</label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                       id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                          id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="Frecuencia" class="form-label">Frecuencia *</label>
                        <select class="form-select @error('Frecuencia') is-invalid @enderror" 
                                id="Frecuencia" name="Frecuencia" required>
                            <option value="">Seleccionar...</option>
                            <option value="Diaria" {{ old('Frecuencia') == 'Diaria' ? 'selected' : '' }}>Diaria</option>
                            <option value="Semanal" {{ old('Frecuencia') == 'Semanal' ? 'selected' : '' }}>Semanal</option>
                            <option value="Quincenal" {{ old('Frecuencia') == 'Quincenal' ? 'selected' : '' }}>Quincenal</option>
                            <option value="Mensual" {{ old('Frecuencia') == 'Mensual' ? 'selected' : '' }}>Mensual</option>
                        </select>
                        @error('Frecuencia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="Horario" class="form-label">Horario</label>
                        <input type="time" class="form-control @error('Horario') is-invalid @enderror" 
                               id="Horario" name="Horario" value="{{ old('Horario') }}">
                        @error('Horario')
                            <div class="invalid-feedback">{{ $message }}</div>

                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="id_habito" class="form-label">Hábito Relacionado</label>
                <select class="form-select @error('id_habito') is-invalid @enderror" 
                        id="id_habito" name="id_habito">
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
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="notificaciones" 
                       name="notificaciones" value="1" {{ old('notificaciones') ? 'checked' : '' }}>
                <label class="form-check-label" for="notificaciones">
                    Activar notificaciones
                </label>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Crear Rutina</button>
        <a href="{{ route('rutinas.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
@endsection