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
    public function index()
    {
        $rutinas = Rutina::with(['habito', 'validaciones'])
            ->where('id_usuario', Auth::user()->id_usuario)
            ->paginate(10);

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
            'descripcion' => 'nullable|string',
            'Frecuencia' => 'required|string|max:50',
            'Horario' => 'nullable|date_format:H:i',
            'id_habito' => 'nullable|exists:Habito,id_habito',
            'notificaciones' => 'boolean'
        ]);

        $rutina = new Rutina($request->all());
        $rutina->id_usuario = Auth::user()->id_usuario;
        $rutina->notificaciones = $request->has('notificaciones');
        $rutina->save();

        return redirect()->route('rutinas.index')
            ->with('success', 'Rutina creada exitosamente.');
    }

    public function show(Rutina $rutina)
    {
        $this->authorize('view', $rutina);
        
        $rutina->load(['habito', 'validaciones' => function($query) {
            $query->orderBy('fecha', 'desc')->limit(10);
        }]);

        return view('rutinas.show', compact('rutina'));
    }

    public function edit(Rutina $rutina)
    {
        $this->authorize('update', $rutina);
        
        $habitos = Habito::where('id_usuario', Auth::user()->id_usuario)->get();
        return view('rutinas.edit', compact('rutina', 'habitos'));
    }

    public function update(Request $request, Rutina $rutina)
    {
        $this->authorize('update', $rutina);

        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'Frecuencia' => 'required|string|max:50',
            'Horario' => 'nullable|date_format:H:i',
            'id_habito' => 'nullable|exists:Habito,id_habito',
            'notificaciones' => 'boolean'
        ]);

        $rutina->fill($request->all());
        $rutina->notificaciones = $request->has('notificaciones');
        $rutina->save();

        return redirect()->route('rutinas.index')
            ->with('success', 'Rutina actualizada exitosamente.');
    }

    public function destroy(Rutina $rutina)
    {
        $this->authorize('delete', $rutina);
        $rutina->delete();

        return redirect()->route('rutinas.index')
            ->with('success', 'Rutina eliminada exitosamente.');
    }

    public function marcarCompletada(Request $request, Rutina $rutina)
    {
        $this->authorize('update', $rutina);

        $hoy = now()->format('Y-m-d');
        
        $validacion = ValidacionRutina::firstOrCreate([
            'id_rutina' => $rutina->id_rutina,
            'fecha' => $hoy
        ]);

        $validacion->completada = !$validacion->completada;
        $validacion->save();

        return response()->json([
            'success' => true,
            'completada' => $validacion->completada,
            'mensaje' => $validacion->completada ? 'Rutina marcada como completada' : 'Rutina marcada como pendiente'
        ]);
    }

    public function dashboard()
{
    return view('rutinas.dashboard');
}
}