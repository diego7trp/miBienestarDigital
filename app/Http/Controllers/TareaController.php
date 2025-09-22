<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
{
    $usuario = Auth::user();
    
    // Usar Query Builder en lugar de Collection
    $query = Tarea::where('id_usuario', $usuario->id_usuario);
    
    // Aplicar filtros si existen
    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }
    
    if ($request->filled('prioridad')) {
        $query->where('prioridad', $request->prioridad);
    }
    
    if ($request->filled('fecha_inicio')) {
        $query->whereDate('fecha_fin', '>=', $request->fecha_inicio);
    }
    
    if ($request->filled('fecha_final')) {
        $query->whereDate('fecha_fin', '<=', $request->fecha_final);
    }
    
    // Ordenar y paginar
    $tareas = $query->orderBy('fecha_fin', 'asc')
                   ->orderBy('prioridad', 'desc')
                   ->paginate(12);
    
    // Estadísticas usando el query builder
    $estadisticas = [
        'total' => Tarea::where('id_usuario', $usuario->id_usuario)->count(),
        'pendientes' => Tarea::where('id_usuario', $usuario->id_usuario)
                             ->where('estado', 'pendiente')->count(),
        'completadas' => Tarea::where('id_usuario', $usuario->id_usuario)
                              ->where('estado', 'completada')->count(),
        'vencidas' => Tarea::where('id_usuario', $usuario->id_usuario)
                           ->where('estado', 'pendiente')
                           ->whereDate('fecha_fin', '<', now())->count(),
        'hoy' => Tarea::where('id_usuario', $usuario->id_usuario)
                      ->where('estado', 'pendiente')
                      ->whereDate('fecha_fin', now())->count(),
    ];

    return view('tareas.index', compact('tareas', 'estadisticas'));
}

    public function create()
    {
        return view('tareas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_fin' => 'required|date|after_or_equal:today',
            'prioridad' => 'required|in:alta,media,baja',
        ]);

        try {
            $tarea = new Tarea();
            $tarea->id_usuario = Auth::user()->id_usuario;
            $tarea->titulo = $request->titulo;
            $tarea->descripcion = $request->descripcion;
            $tarea->fecha_fin = $request->fecha_fin;
            $tarea->prioridad = $request->prioridad;
            $tarea->estado = 'pendiente';
            $tarea->fecha_creacion = now();
            $tarea->save();

            return redirect()->route('tareas.index')
                ->with('success', 'Tarea creada exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear la tarea'])
                        ->withInput();
        }
    }

    public function show($id)
    {
        $tarea = Tarea::where('id_tarea', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();
            
        return view('tareas.show', compact('tarea'));
    }

    public function edit($id)
    {
        $tarea = Tarea::where('id_tarea', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();
            
        return view('tareas.edit', compact('tarea'));
    }

    public function update(Request $request, $id)
    {
        $tarea = Tarea::where('id_tarea', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();

        $request->validate([
            'titulo' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_fin' => 'required|date',
            'prioridad' => 'required|in:alta,media,baja',
            'estado' => 'required|in:pendiente,completada,cancelada'
        ]);

        $tarea->update($request->all());

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea actualizada exitosamente');
    }

    public function destroy($id)
    {
        $tarea = Tarea::where('id_tarea', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();

        $tarea->delete();

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea eliminada exitosamente');
    }

    public function completar($id)
    {
        $tarea = Tarea::where('id_tarea', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();

        $tarea->estado = $tarea->estado === 'completada' ? 'pendiente' : 'completada';
        $tarea->save();

        return response()->json([
            'success' => true,
            'estado' => $tarea->estado,
            'mensaje' => $tarea->estado === 'completada' ? 'Tarea completada' : 'Tarea marcada como pendiente'
        ]);
    }

    public function calendario()
    {
        $tareas = Auth::user()->tareas ?? collect();
        return view('tareas.calendario', compact('tareas'));
    }
}