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
                    <h1 class="welcome-title">Nueva Meta</h1>
                    <p class="welcome-subtitle">Establece un nuevo objetivo de bienestar</p>
                </div>
                <a href="{{ route('metas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <!-- Formulario -->
        <div class="content-section">
            <div class="section-body">
                <form action="{{ route('metas.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="titulo" class="form-label">Título de la Meta *</label>
                            <input type="text" 
                                   class="form-control @error('titulo') is-invalid @enderror" 
                                   id="titulo" 
                                   name="titulo" 
                                   value="{{ old('titulo') }}"
                                   placeholder="Ej: Caminar 10,000 pasos diarios"
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
                                      rows="3"
                                      placeholder="Describe tu meta con más detalle...">{{ old('descripcion') }}</textarea>
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
                                   value="{{ old('fecha_inicio', date('Y-m-d')) }}"
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
                                   value="{{ old('fecha_fin') }}"
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
                                <option value="salud" {{ old('categoria') == 'salud' ? 'selected' : '' }}>
                                    🏥 Salud
                                </option>
                                <option value="ejercicio" {{ old('categoria') == 'ejercicio' ? 'selected' : '' }}>
                                    🏃 Ejercicio
                                </option>
                                <option value="mental" {{ old('categoria') == 'mental' ? 'selected' : '' }}>
                                    🧠 Bienestar Mental
                                </option>
                                <option value="habitos" {{ old('categoria') == 'habitos' ? 'selected' : '' }}>
                                    ✅ Hábitos
                                </option>
                                <option value="otro" {{ old('categoria') == 'otro' ? 'selected' : '' }}>
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
                                <option value="baja" {{ old('prioridad') == 'baja' ? 'selected' : '' }}>
                                    Baja
                                </option>
                                <option value="media" {{ old('prioridad') == 'media' ? 'selected' : '' }}>
                                    Media
                                </option>
                                <option value="alta" {{ old('prioridad') == 'alta' ? 'selected' : '' }}>
                                    Alta
                                </option>
                            </select>
                            @error('prioridad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('metas.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-action-primary">
                            <i class="fas fa-save"></i> Crear Meta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection