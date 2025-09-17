@extends('layouts.auth')

@section('title', 'Registrarse')
@section('subtitle', 'Crea tu cuenta gratuita')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf
    
    <div class="mb-4">
        <label for="nombre" class="form-label">
            <i class="fas fa-user me-2"></i>Nombre Completo
        </label>
        <input type="text" 
               class="form-control @error('nombre') is-invalid @enderror" 
               id="nombre" 
               name="nombre" 
               value="{{ old('nombre') }}" 
               required 
               autofocus>
        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="correo" class="form-label">
            <i class="fas fa-envelope me-2"></i>Correo Electrónico
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

    <div class="mb-4">
        <label for="password" class="form-label">
            <i class="fas fa-lock me-2"></i>Contraseña
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

    <div class="mb-4">
        <label for="password_confirmation" class="form-label">
            <i class="fas fa-lock me-2"></i>Confirmar Contraseña
        </label>
        <input type="password" 
               class="form-control" 
               id="password_confirmation" 
               name="password_confirmation" 
               required>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-user-plus me-2"></i>Crear Cuenta
        </button>
    </div>

    <div class="text-center mt-4">
        <p class="text-muted">¿Ya tienes cuenta?</p>
        <a href="{{ route('login') }}" class="text-decoration-none">
            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
        </a>
    </div>
</form>
@endsection