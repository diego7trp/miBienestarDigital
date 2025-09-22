@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Editar Tarea</h4>
                    <a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{ route('tareas.update', $tarea->id_tarea) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título *</label>
                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                   id="titulo" name="titulo" value="{{ old('titulo', $tarea->titulo) }}" required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $tarea->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha de Vencimiento *</label>
                                    <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" 
                                           id="fecha_fin" name="fecha_fin" 
                                           value="{{ old('fecha_fin', $tarea->fecha_fin->format('Y-m-d')) }}" required>
                                    @error('fecha_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="prioridad" class="form-label">Prioridad *</label>
                                    <select class="form-select @error('prioridad') is-invalid @enderror" 
                                            id="prioridad" name="prioridad" required>
                                        <option value="alta" {{ old('prioridad', $tarea->prioridad) == 'alta' ? 'selected' : '' }}>Alta</option>
                                        <option value="media" {{ old('prioridad', $tarea->prioridad) == 'media' ? 'selected' : '' }}>Media</option>
                                        <option value="baja" {{ old('prioridad', $tarea->prioridad) == 'baja' ? 'selected' : '' }}>Baja</option>
                                    </select>
                                    @error('prioridad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado *</label>
                                    <select class="form-select @error('estado') is-invalid @enderror" 
                                            id="estado" name="estado" required>
                                        <option value="pendiente" {{ old('estado', $tarea->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="completada" {{ old('estado', $tarea->estado) == 'completada' ? 'selected' : '' }}>Completada</option>
                                        <option value="cancelada" {{ old('estado', $tarea->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('tareas.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Tarea</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection