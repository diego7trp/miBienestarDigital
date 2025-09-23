@extends('layouts.app')

@section('title', 'Lista de Especialistas')

@section('content')
<h2 class="text-3xl font-bold text-center mb-10 text-purple-700">Lista de Especialistas</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($especialistas as $especialista)
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition transform hover:scale-105">
            <h3 class="text-xl font-bold text-gray-800">{{ $especialista->nombre }}</h3>
            <p class="text-green-600 font-medium">{{ $especialista->especialidad }}</p>
            <p class="text-gray-500 text-sm mb-4">{{ $especialista->email }}</p>
            <a href="{{ route('especialistas.show', $especialista->id) }}" 
               class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg shadow-md">
               Ver información
            </a>
        </div>
    @endforeach
</div>
@endsection


 


