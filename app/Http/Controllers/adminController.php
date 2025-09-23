<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Aquí podrías validar que solo los usuarios con rol "admin" entren
    }

    public function index()
    {
        // Total de usuarios
        $usuarios = User::all();
        $usuariosNormales = User::where('rol', 'user')->get();
        $administradores = User::where('rol', 'admin')->get();

        return view('admin.dashboard', compact('usuarios', 'usuariosNormales', 'administradores'));
    }
}

