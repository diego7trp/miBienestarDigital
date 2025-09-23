<?php

namespace App\Http\Controllers;

use App\Models\Consejo;
use Illuminate\Http\Request;

class ConsejoController extends Controller
{
    public function index()
    {
        // Traemos todos los consejos desde la BD
        $consejos = Consejo::all();

        // Enviamos la variable a la vista
        return view('consejos.index', compact('consejos'));
    }

    public function show($id)
    {
        $consejo = Consejo::findOrFail($id);

        return view('consejos.show', compact('consejo'));
    }
}



