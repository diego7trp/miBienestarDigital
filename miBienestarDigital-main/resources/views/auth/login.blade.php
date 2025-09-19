@extends('layouts.auth')

@section('title', 'Iniciar Sesión')
@section('subtitle', 'Accede a tu cuenta')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    
    <div class="mb-4">
        <label for="correo" class="form-label">
            <i class="fas fa-envelope me-2"></i>Correo Electrónico
        </label>
        <input type="email" 
               class="form-control @error('correo') is-invalid @enderror" 
               id="correo" 
               name="correo" 
               value="{{ old('correo') }}" 
               required 
               autofocus>
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

    <div class="mb-4 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">
            Recordarme
        </label>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
        </button>
    </div>

    <div class="text-center mt-4">
        <p class="text-muted">¿No tienes cuenta?</p>
        <a href="{{ route('register') }}" class="text-decoration-none">
            <i class="fas fa-user-plus me-2"></i>Registrarse
        </a>
    </div>
</form>
@endsection