@extends('layouts.app')

@section('title', 'Detalles del Especialista')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-xl rounded-xl p-8">
    <h2 class="text-2xl font-bold text-purple-700 mb-4">{{ $especialista->nombre }}</h2>
    <p class="text-green-600 font-semibold">{{ $especialista->especialidad }}</p>
    <p class="text-gray-600 mb-6">{{ $especialista->email }}</p>

    <h3 class="text-xl font-bold text-gray-800 mb-4">Consejos del especialista</h3>
    @if($especialista->consejos->count() > 0)
        <div class="space-y-4">
            @foreach($especialista->consejos as $consejo)
                <div class="bg-gray-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                    <h4 class="text-lg font-semibold text-purple-600">{{ $consejo->titulo }}</h4>
                    <p class="text-gray-700">{{ $consejo->contenido }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 italic">Este especialista aún no tiene consejos.</p>
    @endif

    <div class="mt-6">
        <a href="{{ route('especialistas.index') }}" 
           class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md">
           ← Volver a la lista
        </a>
    </div>
</div>
@endsection



