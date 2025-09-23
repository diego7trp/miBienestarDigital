@extends('layouts.app')

@section('title', 'Lista de Consejos')

@section('content')
<div class="max-w-5xl mx-auto">
    <h1 class="text-3xl font-bold text-center text-purple-700 mb-8">
        🌿 Lista de Consejos
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($consejos as $consejo)
            <div class="bg-white shadow-lg rounded-xl p-6 hover:shadow-2xl transition">
                <h2 class="text-xl font-semibold text-green-600">{{ $consejo->titulo }}</h2>
                <p class="text-gray-600 mt-2 line-clamp-2">{{ $consejo->contenido }}</p>
                
                <a href="{{ route('consejos.show', $consejo->id) }}"
                   class="mt-4 inline-block bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Ver detalle
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection



