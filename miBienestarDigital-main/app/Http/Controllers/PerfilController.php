<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\HabitoObjetivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $usuario = Auth::user();
        $usuario->load('habitosObjetivo');
        
        return view('perfil.show', compact('usuario'));
    }

    public function edit()
    {
        $usuario = Auth::user();
        $usuario->load('habitosObjetivo');
        
        $habitosDisponibles = [
            'Mejorar alimentación',
            'Hacer ejercicio regularmente',
            'Dormir mejor',
            'Reducir el estrés',
            'Reducir el uso de pantallas',
            'Otro'
        ];
        
        return view('perfil.edit', compact('usuario', 'habitosDisponibles'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'genero' => 'nullable|in:masculino,femenino,otro,prefiero_no_decir',
            'edad' => 'nullable|integer|min:13|max:120',
            'peso' => 'nullable|numeric|min:20|max:500',
            'altura' => 'nullable|integer|min:100|max:250',
            'telefono' => 'nullable|string|max:20',
            'condiciones_medicas' => 'nullable|string|max:1000',
            'habitos_objetivo' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $usuario = Auth::user();
            
            // Actualizar datos del usuario
            $usuario->update([
                'nombre' => $request->nombre,
                'genero' => $request->genero,
                'edad' => $request->edad,
                'peso' => $request->peso,
                'altura' => $request->altura,
                'telefono' => $request->telefono,
                'condiciones_medicas' => $request->condiciones_medicas,
                'perfil_completado' => $request->filled(['genero', 'edad', 'peso', 'altura'])
            ]);

            // Actualizar hábitos objetivo
            $usuario->habitosObjetivo()->delete();
            
            if ($request->has('habitos_objetivo')) {
                foreach ($request->habitos_objetivo as $habito) {
                    HabitoObjetivo::create([
                        'id_usuario' => $usuario->id_usuario,
                        'nombre_habito' => $habito
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('perfil.show')->with('success', 'Perfil actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar el perfil'])->withInput();
        }
    }
}