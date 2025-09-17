@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Mis Rutinas</h2>
    <a href="{{ route('rutinas.create') }}" class="btn btn-primary">Nueva Rutina</a>
</div>

<div class="row">
    @forelse($rutinas as $rutina)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $rutina->nombre }}</h5>
                @php
                    $validacionHoy = $rutina->validaciones->where('fecha', now()->format('Y-m-d'))->first();
                    $completada = $validacionHoy && $validacionHoy->completada;
                @endphp
                <button class="btn btn-sm {{ $completada ? 'btn-success' : 'btn-outline-secondary' }} btn-toggle-rutina" 
                        data-rutina-id="{{ $rutina->id_rutina }}">
                    {{ $completada ? '✓' : '○' }}
                </button>
            </div>
            <div class="card-body">
                <p class="card-text">{{ Str::limit($rutina->descripcion, 80) }}</p>
                <small class="text-muted">
                    <strong>Frecuencia:</strong> {{ $rutina->Frecuencia }}<br>
                    @if($rutina->Horario)
                        <strong>Horario:</strong> {{ $rutina->Horario->format('H:i') }}<br>
                    @endif
                    @if($rutina->habito)
                        <strong>Hábito:</strong> {{ $rutina->habito->nombre }}
                    @endif
                </small>
            </div>
            <div class="card-footer">
                <a href="{{ route('rutinas.show', $rutina->id_rutina) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                <a href="{{ route('rutinas.edit', $rutina->id_rutina) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                <form method="POST" action="{{ route('rutinas.destroy', $rutina->id_rutina) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                            onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">
            No tienes rutinas creadas. <a href="{{ route('rutinas.create') }}">Crea tu primera rutina</a>
        </div>
    </div>
    @endforelse
</div>

{{ $rutinas->links() }}
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.btn-toggle-rutina').click(function() {
        const rutinaId = $(this).data('rutina-id');
        const button = $(this);
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.post(`/rutinas/${rutinaId}/completar`, function(data) {
            if(data.success) {
                button.toggleClass('btn-success btn-outline-secondary');
                button.text(data.completada ? '✓' : '○');
            }
        });
    });
});
</script>
@endsection