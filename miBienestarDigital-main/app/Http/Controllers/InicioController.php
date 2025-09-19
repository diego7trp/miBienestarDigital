<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index()
    {
        return view('inicio'); // va a buscar la vista inicio.blade.php
    }
}
