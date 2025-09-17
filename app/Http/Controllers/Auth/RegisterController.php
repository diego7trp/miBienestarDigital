<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'correo' => 'required|string|email|max:50|unique:Usuario,correo',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // Obtener ID del rol paciente
            $rolPaciente = Rol::where('nombre_rol', 'Paciente')->first();
            
            if (!$rolPaciente) {
                throw new \Exception('Rol de Paciente no encontrado');
            }

            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'password_hash' => $request->password, // Se hashea automáticamente en el mutador
                'id_rol' => $rolPaciente->id_rol,
            ]);

            DB::commit();

            Auth::login($usuario);

            return redirect('dashboard')->with('success', '¡Cuenta creada exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Error al crear la cuenta: ' . $e->getMessage()
            ]);
        }
    }
}