@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-edit me-2"></i>Editar Tarea</h4>
                <div class="btn-group">
                    <a href="{{ route('tareas.show', $tarea->id_tarea) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-eye me-2"></i>Ver
                    </a>
                    <a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tareas.update', $tarea->id_tarea) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="titulo" class="form-label">
                            <i class="fas fa-heading me-2 text-primary"></i>Título de la Tarea *
                        </label>
                        <input type="text" 
                               class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" 
                               name="titulo" 
                               value="{{ old('titulo', $tarea->titulo) }}" 
                               required 
                               maxlength="100">
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="form-label">
                            <i class="fas fa-align-left me-2 text-info"></i>Descripción
                        </label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" 
                                  name="descripcion" 
                                  rows="4"
                                  maxlength="1000">{{ old('descripcion', $tarea->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="fecha_fin" class="form-label">
                                    <i class="fas fa-calendar me-2 text-warning"></i>Fecha de Vencimiento *
                                </label>
                                <input type="date" 
                                       class="form-control @error('fecha_fin') is-invalid @enderror" 
                                       id="fecha_fin" 
                                       name="fecha_fin" 
                                       value="{{ old('fecha_fin', $tarea->fecha_fin->format('Y-m-d')) }}"
                                       required>
                                @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="prioridad" class="form-label">
                                    <i class="fas fa-flag me-2 text-danger"></i>Prioridad *
                                </label>
                                <select class="form-select @error('prioridad') is-invalid @enderror" 
                                        id="prioridad" 
                                        name="prioridad" 
                                        required>
                                    <option value="alta" {{ old('prioridad', $tarea->prioridad) == 'alta' ? 'selected' : '' }}>
                                        🔴 Alta
                                    </option>
                                    <option value="media" {{ old('prioridad', $tarea->prioridad) == 'media' ? 'selected' : '' }}>
                                        🟡 Media
                                    </option>
                                    <option value="baja" {{ old('prioridad', $tarea->prioridad) == 'baja' ? 'selected' : '' }}>
                                        🔵 Baja
                                    </option>
                                </select>
                                @error('prioridad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="estado" class="form-label">
                                    <i class="fas fa-tasks me-2 text-success"></i>Estado *
                                </label>
                                <select class="form-select @error('estado') is-invalid @enderror" 
                                        id="estado" 
                                        name="estado" 
                                        required>
                                    <option value="pendiente" {{ old('estado', $tarea->estado) == 'pendiente' ? 'selected' : '' }}>
                                        ⏳ Pendiente
                                    </option>
                                    <option value="completada" {{ old('estado', $tarea->estado) == 'completada' ? 'selected' : '' }}>
                                        ✅ Completada
                                    </option>
                                    <option value="cancelada" {{ old('estado', $tarea->estado) == 'cancelada' ? 'selected' : '' }}>
                                        ❌ Cancelada
                                    </option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
        <div class="stat-card-task total">
            <div class="stat-icon">
                <i class="fas fa-list"></i>
            </div>
            <h3 class="stat-number">{{ $estadisticas['total'] }}</h3>
            <p class="stat-label">Total</p>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="stat-card-task pendientes">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h3 class="stat-number">{{ $estadisticas['pendientes'] }}</h3>
            <p class="stat-label">Pendientes</p>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="stat-card-task completadas">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 class="stat-number">{{ $estadisticas['completadas'] }}</h3>
            <p class="stat-label">Completadas</p>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="stat-card-task vencidas">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="stat-number">{{ $estadisticas['vencidas'] }}</h3>
            <p class="stat-label">Vencidas</p>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-4 col-6 mb-3">