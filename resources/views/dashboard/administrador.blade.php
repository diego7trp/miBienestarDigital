@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Panel de Administración</h1>

    <div class="row">
        <!-- Panel del Administrador -->
        <div class="col-md-3">
            <div class="card text-bg-dark mb-3">
                <div class="card-header">Panel del Administrador</div>
                <div class="card-body">
                    <h5 class="card-title">Resumen</h5>
                    <p class="card-text">Aquí podrás gestionar todo el sistema.</p>
                </div>
            </div>
        </div>

        <!-- Gestión de Usuarios -->
        <div class="col-md-3">
            <div class="card text-bg-primary mb-3">
                <div class="card-header">Gestión de Usuarios</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $usuarios->count() }} Usuarios</h5>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-light btn-sm">Ver Todos</a>
                </div>
            </div>
        </div>

        <!-- Usuarios normales -->
        <div class="col-md-3">
            <div class="card text-bg-success mb-3">
                <div class="card-header">Usuarios</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $usuariosNormales->count() }} Usuarios normales</h5>
                    <ul>
                        @foreach($usuariosNormales as $u)
                            <li>{{ $u->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Administradores -->
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-header">Administradores</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $administradores->count() }} Admins</h5>
                    <ul>
                        @foreach($administradores as $a)
                            <li>{{ $a->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

