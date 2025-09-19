<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
use App\Models\Habito;
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
        $habitosDisponibles = [
            'Fumar',
            'Comer en exceso',
            'Trasnochar',
            'Uso excesivo de pantallas',
            'Sedentarismo',
            'Procrastinar',
            'Consumo excesivo de alcohol',
            'Mal manejo del estrés',
            'Otro'
        ];
        
        return view('auth.register', compact('habitosDisponibles'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'correo' => 'required|string|email|max:50|unique:Usuario,correo',
            'password' => 'required|string|min:8|confirmed',
            'genero' => 'nullable|in:masculino,femenino,otro,prefiero_no_decir',
            'edad' => 'nullable|integer|min:13|max:120',
            'peso' => 'nullable|numeric|min:20|max:500',
            'altura' => 'nullable|integer|min:100|max:250',
            'telefono' => 'nullable|string|max:20',
            'condiciones_medicas' => 'nullable|string|max:1000',
            'malos_habitos' => 'nullable|array',
            'malos_habitos.*' => 'string|max:50'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'edad.min' => 'Debes tener al menos 13 años',
            'edad.max' => 'Por favor verifica tu edad',
            'peso.min' => 'El peso debe ser mayor a 20 kg',
            'peso.max' => 'Por favor verifica tu peso',
            'altura.min' => 'La altura debe ser mayor a 100 cm',
            'altura.max' => 'Por favor verifica tu altura'
        ]);

        try {
            DB::beginTransaction();

            // Obtener ID del rol paciente
            $rolPaciente = Rol::where('nombre_rol', 'Paciente')->first();
            
            if (!$rolPaciente) {
                throw new \Exception('Rol de Paciente no encontrado');
            }

            // Crear usuario
            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'password_hash' => $request->password,
                'id_rol' => $rolPaciente->id_rol,
                'genero' => $request->genero,
                'edad' => $request->edad,
                'peso' => $request->peso,
                'altura' => $request->altura,
                'telefono' => $request->telefono,
                'condiciones_medicas' => $request->condiciones_medicas,
                'perfil_completado' => $this->esPerfilCompleto($request)
            ]);

            // Guardar malos hábitos en la tabla Habito existente
            if ($request->has('malos_habitos')) {
                foreach ($request->malos_habitos as $habito) {
                    Habito::create([
                        'nombre' => $habito,
                        'descripcion' => 'Hábito identificado durante el registro',
                        'id_usuario' => $usuario->id_usuario
                    ]);
                }
            }

            DB::commit();

            Auth::login($usuario);

            $mensaje = $usuario->perfil_completado 
                ? '¡Cuenta creada exitosamente! Tu perfil está completo.' 
                : '¡Cuenta creada exitosamente! Puedes completar tu perfil más tarde.';

            return redirect('dashboard')->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Error al crear la cuenta: ' . $e->getMessage()
            ])->withInput();
        }
    }

    private function esPerfilCompleto($request)
    {
        return $request->filled(['genero', 'edad', 'peso', 'altura']);
    }
}