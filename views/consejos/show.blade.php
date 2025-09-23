@extends('layouts.app')

@section('title', 'Detalle del Consejo')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8">
    <h1 class="text-3xl font-bold text-purple-700 mb-4">
        {{ $consejo->titulo }}
    </h1>
    <p class="text-gray-700 text-lg leading-relaxed mb-6">
        {{ $consejo->contenido }}
    </p>

    <div class="flex justify-between items-center">
        <a href="{{ route('consejos.index') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow font-medium transition">
           ← Volver a la lista
        </a>

        <span class="text-sm text-gray-500">
            Creado: {{ $consejo->created_at->format('d/m/Y') }}
        </span>
    </div>
</div>
@endsection



