<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetaController extends Controller
{
    public function index()
    {
        $metas = Auth::user()->metas()->latest()->get();
        
        $estadisticas = [
            'total' => $metas->count(),
            'completadas' => $metas->where('estado', 'completada')->count(),
            'en_progreso' => $metas->where('estado', 'en_progreso')->count(),
            'pendientes' => $metas->where('estado', 'pendiente')->count(),
        ];

        return view('metas.index', compact('metas', 'estadisticas'));
    }

    public function create()
    {
        return view('metas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'categoria' => 'required|in:salud,ejercicio,mental,habitos,otro',
            'prioridad' => 'required|in:baja,media,alta',
        ]);

        $validated['id_usuario'] = Auth::id(); // Cambiar de paciente_id
        $validated['estado'] = 'pendiente';
        $validated['progreso'] = 0;

        Meta::create($validated);

        return redirect()->route('metas.index')
            ->with('success', '¡Meta creada exitosamente!');
    }

    public function show(Meta $meta)
    {
        // Verificar que la meta pertenece al usuario autenticado
        // Usar el valor directo del primaryKey
        if ($meta->id_usuario !== Auth::user()->id_usuario) {
            abort(403);
        }

        return view('metas.show', compact('meta'));
    }

    public function edit(Meta $meta)
    {
        // Verificar que la meta pertenece al usuario autenticado
        if ($meta->id_usuario !== Auth::user()->id_usuario) {
            abort(403);
        }

        return view('metas.edit', compact('meta'));
    }

    public function update(Request $request, Meta $meta)
    {
        // Verificar que la meta pertenece al usuario autenticado
        if ($meta->id_usuario !== Auth::user()->id_usuario) {
            abort(403);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:pendiente,en_progreso,completada,cancelada',
            'progreso' => 'required|integer|min:0|max:100',
            'categoria' => 'required|in:salud,ejercicio,mental,habitos,otro',
            'prioridad' => 'required|in:baja,media,alta',
        ]);

        // Si el progreso es 100, marcar como completada
        if ($validated['progreso'] == 100) {
            $validated['estado'] = 'completada';
        }

        $meta->update($validated);

        return redirect()->route('metas.index')
            ->with('success', '¡Meta actualizada exitosamente!');
    }

    public function destroy(Meta $meta)
    {
        // Verificar que la meta pertenece al usuario autenticado
        if ($meta->id_usuario !== Auth::id()) {
            abort(403);
        }

        $meta->delete();

        return redirect()->route('metas.index')
            ->with('success', '¡Meta eliminada exitosamente!');
    }

    // Actualizar progreso rápidamente
    public function updateProgreso(Request $request, Meta $meta)
    {
        if ($meta->id_usuario !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'progreso' => 'required|integer|min:0|max:100'
        ]);

        $meta->progreso = $validated['progreso'];
        
        if ($validated['progreso'] == 100) {
            $meta->estado = 'completada';
        } elseif ($validated['progreso'] > 0) {
            $meta->estado = 'en_progreso';
        }

        $meta->save();

        return back()->with('success', 'Progreso actualizado');
    }
}