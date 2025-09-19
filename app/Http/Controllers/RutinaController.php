<?php

namespace App\Http\Controllers;

use App\Models\Rutina;
use App\Models\Habito;
use App\Models\ValidacionRutina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RutinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $rutinas = Rutina::with(['habito', 'validaciones' => function($query) {
                $query->where('fecha', today());
            }])
            ->where('id_usuario', Auth::user()->id_usuario)
            ->orderBy('id_rutina', 'desc')
            ->paginate(12);

        return view('rutinas.index', compact('rutinas'));
    }

    public function create()
    {
        $habitos = Habito::where('id_usuario', Auth::user()->id_usuario)->get();
        return view('rutinas.create', compact('habitos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:1000',
            'Frecuencia' => 'required|string|max:50',
            'Horario' => 'nullable|date_format:H:i',
            'id_habito' => 'nullable|exists:Habito,id_habito',
            'notificaciones' => 'boolean'
        ], [
            'nombre.required' => 'El nombre de la rutina es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres',
            'Frecuencia.required' => 'La frecuencia es obligatoria',
            'Horario.date_format' => 'El horario debe tener el formato correcto (HH:MM)',
            'id_habito.exists' => 'El hábito seleccionado no es válido'
        ]);

        try {
            $rutina = new Rutina();
            $rutina->id_usuario = Auth::user()->id_usuario;
            $rutina->nombre = $request->nombre;
            $rutina->descripcion = $request->descripcion;
            $rutina->Frecuencia = $request->Frecuencia;
            $rutina->Horario = $request->Horario;
            $rutina->id_habito = $request->id_habito;
            $rutina->notificaciones = $request->has('notificaciones') ? 1 : 0;
            $rutina->fecha_creacion = now();
            
            $rutina->save();

            return redirect()->route('rutinas.index')
                ->with('success', '✅ Rutina creada exitosamente');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear la rutina: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function show($id)
    {
        $rutina = Rutina::with(['habito', 'validaciones' => function($query) {
                $query->orderBy('fecha', 'desc');
            }])
            ->where('id_rutina', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();

        // Estadísticas de la rutina
        $estadisticas = [
            'total_dias' => $rutina->validaciones->count(),
            'dias_completados' => $rutina->validaciones->where('completada', true)->count(),
            'dias_fallados' => $rutina->validaciones->where('completada', false)->count(),
            'racha_actual' => $this->calcularRachaActual($rutina),
            'ultima_actividad' => $rutina->validaciones->first()
        ];

        return view('rutinas.show', compact('rutina', 'estadisticas'));
    }

    public function edit($id)
    {
        $rutina = Rutina::where('id_rutina', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();
            
        $habitos = Habito::where('id_usuario', Auth::user()->id_usuario)->get();
        
        return view('rutinas.edit', compact('rutina', 'habitos'));
    }

    public function update(Request $request, $id)
    {
        $rutina = Rutina::where('id_rutina', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:1000',
            'Frecuencia' => 'required|string|max:50',
            'Horario' => 'nullable|date_format:H:i',
            'id_habito' => 'nullable|exists:Habito,id_habito',
            'notificaciones' => 'boolean'
        ], [
            'nombre.required' => 'El nombre de la rutina es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres',
            'Frecuencia.required' => 'La frecuencia es obligatoria',
            'Horario.date_format' => 'El horario debe tener el formato correcto (HH:MM)',
            'id_habito.exists' => 'El hábito seleccionado no es válido'
        ]);

        try {
            $rutina->nombre = $request->nombre;
            $rutina->descripcion = $request->descripcion;
            $rutina->Frecuencia = $request->Frecuencia;
            $rutina->Horario = $request->Horario;
            $rutina->id_habito = $request->id_habito;
            $rutina->notificaciones = $request->has('notificaciones') ? 1 : 0;
            
            $rutina->save();

            return redirect()->route('rutinas.index')
                ->with('success', '✅ Rutina actualizada exitosamente');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar la rutina: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function destroy($id)
    {
        $rutina = Rutina::where('id_rutina', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();

        try {
            // Eliminar validaciones relacionadas primero
            $rutina->validaciones()->delete();
            
            // Eliminar la rutina
            $nombreRutina = $rutina->nombre;
            $rutina->delete();

            return redirect()->route('rutinas.index')
                ->with('success', "🗑️ Rutina '{$nombreRutina}' eliminada exitosamente");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar la rutina: ' . $e->getMessage()]);
        }
    }

    public function marcarCompletada(Request $request, $id)
    {
        $rutina = Rutina::where('id_rutina', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();

        $hoy = now()->format('Y-m-d');
        
        try {
            $validacion = ValidacionRutina::firstOrCreate([
                'id_rutina' => $rutina->id_rutina,
                'fecha' => $hoy
            ], [
                'completada' => false
            ]);

            $validacion->completada = !$validacion->completada;
            $validacion->save();

            return response()->json([
                'success' => true,
                'completada' => $validacion->completada,
                'mensaje' => $validacion->completada 
                    ? '✅ Rutina marcada como completada' 
                    : '⏳ Rutina marcada como pendiente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al actualizar la rutina'
            ], 500);
        }
    }

    public function dashboard()
    {
        return view('rutinas.dashboard');
    }

    private function calcularRachaActual($rutina)
    {
        $validaciones = $rutina->validaciones()
            ->orderBy('fecha', 'desc')
            ->get();

        $racha = 0;
        foreach ($validaciones as $validacion) {
            if ($validacion->completada) {
                $racha++;
            } else {
                break;
            }
        }

        return $racha;
    }
}