@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar-dashboard">
        <div class="sidebar-header">
            <h4 class="sidebar-title">Paciente</h4>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-link-custom">
                <i class="fas fa-home"></i>Inicio
            </a>
            <a href="{{ route('rutinas.index') }}" class="nav-link-custom">
                <i class="fas fa-calendar-day"></i>Mis Rutinas
            </a>
            @if(Route::has('tareas.index'))
            <a href="{{ route('tareas.index') }}" class="nav-link-custom">
                <i class="fas fa-tasks"></i>Tareas
            </a>
            @endif
            <a href="{{ route('metas.index') }}" class="nav-link-custom active">
                <i class="fas fa-bullseye"></i>Metas
            </a>
            <a href="#" class="nav-link-custom">
                <i class="fas fa-lightbulb"></i>Consejos
            </a>
            
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem;">
            
            <a href="{{ route('logout') }}" class="nav-link-custom" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>Cerrar Sesión
            </a>
        </nav>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <!-- Header -->
        <div class="content-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 class="welcome-title">Editar Meta</h1>
                    <p class="welcome-subtitle">Modifica los detalles de tu objetivo</p>
                </div>
                <a href="{{ route('metas.show', $meta) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </div>

        <!-- Formulario -->
        <div class="content-section">
            <div class="section-body">
                <form action="{{ route('metas.update', $meta) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="titulo" class="form-label">Título de la Meta *</label>
                            <input type="text" 
                                   class="form-control @error('titulo') is-invalid @enderror" 
                                   id="titulo" 
                                   name="titulo" 
                                   value="{{ old('titulo', $meta->titulo) }}"
                                   required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="3">{{ old('descripcion', $meta->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio *</label>
                            <input type="date" 
                                   class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                   id="fecha_inicio" 
                                   name="fecha_inicio" 
                                   value="{{ old('fecha_inicio', $meta->fecha_inicio->format('Y-m-d')) }}"
                                   required>
                            @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Finalización *</label>
                            <input type="date" 
                                   class="form-control @error('fecha_fin') is-invalid @enderror" 
                                   id="fecha_fin" 
                                   name="fecha_fin" 
                                   value="{{ old('fecha_fin', $meta->fecha_fin->format('Y-m-d')) }}"
                                   required>
                            @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="categoria" class="form-label">Categoría *</label>
                            <select class="form-select @error('categoria') is-invalid @enderror" 
                                    id="categoria" 
                                    name="categoria" 
                                    required>
                                <option value="">Seleccionar categoría</option>
                                <option value="salud" {{ old('categoria', $meta->categoria) == 'salud' ? 'selected' : '' }}>
                                    🏥 Salud
                                </option>
                                <option value="ejercicio" {{ old('categoria', $meta->categoria) == 'ejercicio' ? 'selected' : '' }}>
                                    🏃 Ejercicio
                                </option>
                                <option value="mental" {{ old('categoria', $meta->categoria) == 'mental' ? 'selected' : '' }}>
                                    🧠 Bienestar Mental
                                </option>
                                <option value="habitos" {{ old('categoria', $meta->categoria) == 'habitos' ? 'selected' : '' }}>
                                    ✅ Hábitos
                                </option>
                                <option value="otro" {{ old('categoria', $meta->categoria) == 'otro' ? 'selected' : '' }}>
                                    📌 Otro
                                </option>
                            </select>
                            @error('categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="prioridad" class="form-label">Prioridad *</label>
                            <select class="form-select @error('prioridad') is-invalid @enderror" 
                                    id="prioridad" 
                                    name="prioridad" 
                                    required>
                                <option value="">Seleccionar prioridad</option>
                                <option value="baja" {{ old('prioridad', $meta->prioridad) == 'baja' ? 'selected' : '' }}>
                                    Baja
                                </option>
                                <option value="media" {{ old('prioridad', $meta->prioridad) == 'media' ? 'selected' : '' }}>
                                    Media
                                </option>
                                <option value="alta" {{ old('prioridad', $meta->prioridad) == 'alta' ? 'selected' : '' }}>
                                    Alta
                                </option>
                            </select>
                            @error('prioridad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select @error('estado') is-invalid @enderror" 
                                    id="estado" 
                                    name="estado" 
                                    required>
                                <option value="pendiente" {{ old('estado', $meta->estado) == 'pendiente' ? 'selected' : '' }}>
                                    Pendiente
                                </option>
                                <option value="en_progreso" {{ old('estado', $meta->estado) == 'en_progreso' ? 'selected' : '' }}>
                                    En Progreso
                                </option>
                                <option value="completada" {{ old('estado', $meta->estado) == 'completada' ? 'selected' : '' }}>
                                    Completada
                                </option>
                                <option value="cancelada" {{ old('estado', $meta->estado) == 'cancelada' ? 'selected' : '' }}>
                                    Cancelada
                                </option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="progreso" class="form-label">Progreso (%) *</label>
                            <input type="number" 
                                   class="form-control @error('progreso') is-invalid @enderror" 
                                   id="progreso" 
                                   name="progreso" 
                                   value="{{ old('progreso', $meta->progreso) }}"
                                   min="0"
                                   max="100"
                                   required>
                            @error('progreso')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('metas.show', $meta) }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-action-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection