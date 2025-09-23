<?php

namespace App\Http\Controllers;

use App\Models\Especialista;
use Illuminate\Http\Request;

class EspecialistaController extends Controller
{
    // Mostrar todos los especialistas
    public function index()
    {
        $especialistas = Especialista::all();
        return view('especialistas.index', compact('especialistas'));
    }

    // Mostrar un especialista con sus consejos
    public function show($id)
    {
        $especialista = Especialista::with('consejos')->findOrFail($id);
        return view('especialistas.show', compact('especialista'));
    }
}

