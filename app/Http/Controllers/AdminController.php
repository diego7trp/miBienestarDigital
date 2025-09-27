<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Muestra el panel del administrador
     */
    public function admin_panel()
    {
        return view('admin.admin_panel');
    }

    /**
     * Muestra la vista de usuarios
     */
    public function admin_usuario()
    {
        return view('admin.admin_usuario');
    }

    /**
     * Muestra la vista de gestión de usuarios
     */
    public function admin_gestionUsuario()
    {
        return view('admin.admin_gestionUsuario');
    }

    /**
     * Muestra la vista principal del administrador
     */
    public function administrador()
    {
        return view('admin.administrador');
    }
}
