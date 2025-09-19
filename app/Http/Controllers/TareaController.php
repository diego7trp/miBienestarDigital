<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Tarea::where('id_usuario', Auth::user()->id_usuario);
        
        // Filtros
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
        
        // Ordenar por prioridad y fecha
        $tareas = $query->orderByRaw("
            CASE 
                WHEN estado = 'pendiente' AND fecha_fin < CURDATE() THEN 1
                WHEN estado = 'pendiente' AND fecha_fin = CURDATE() THEN 2
                WHEN estado = 'pendiente' THEN 3
                ELSE 4
            END,
            CASE prioridad 
                WHEN 'alta' THEN 1 
                WHEN 'media' THEN 2 
                WHEN 'baja' THEN 3 
                ELSE 4 
            END,
            fecha_fin ASC
        ")->paginate(12);
        
        // Estadísticas
        $estadisticas = [
            'total' => Auth::user()->tareas->count(),
            'pendientes' => Auth::user()->tareas->where('estado', 'pendiente')->count(),
            'completadas' => Auth::user()->tareas->where('estado', 'completada')->count(),
            'vencidas' => Auth::user()->tareas()
                ->where('estado', 'pendiente')
                ->whereDate('fecha_fin', '<', now())
                ->count(),
            'hoy' => Auth::user()->tareas()
                ->where('estado', 'pendiente')
                ->whereDate('fecha_fin', now())
                ->count(),
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
        ], [
            'titulo.required' => 'El título es obligatorio',
            'titulo.max' => 'El título no puede tener más de 100 caracteres',
            'fecha_fin.required' => 'La fecha de vencimiento es obligatoria',
            'fecha_fin.after_or_equal' => 'La fecha no puede ser anterior a hoy',
            'prioridad.required' => 'La prioridad es obligatoria',
            'prioridad.in' => 'La prioridad debe ser alta, media o baja',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres'
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
                ->with('success', '✅ Tarea creada exitosamente');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear la tarea: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function show($id)
    {
        $tarea = Tarea::where('id_tarea', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();
            
        // Calcular días restantes/vencidos
        $fechaFin = Carbon::parse($tarea->fecha_fin);
        $hoy = Carbon::now();
        
        $diasRestantes = $hoy->diffInDays($fechaFin, false);
        $esVencida = $tarea->estado === 'pendiente' && $fechaFin->isPast();
        $esHoy = $fechaFin->isToday();
        
        $estadoTiempo = [
            'dias_restantes' => $diasRestantes,
            'es_vencida' => $esVencida,
            'es_hoy' => $esHoy,
            'fecha_formateada' => $fechaFin->format('d/m/Y'),
            'fecha_relativa' => $fechaFin->diffForHumans()
        ];

        return view('tareas.show', compact('tarea', 'estadoTiempo'));
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
        ], [
            'titulo.required' => 'El título es obligatorio',
            'titulo.max' => 'El título no puede tener más de 100 caracteres',
            'fecha_fin.required' => 'La fecha de vencimiento es obligatoria',
            'prioridad.required' => 'La prioridad es obligatoria',
            'estado.required' => 'El estado es obligatorio',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres'
        ]);

        try {
            $tarea->titulo = $request->titulo;
            $tarea->descripcion = $request->descripcion;
            $tarea->fecha_fin = $request->fecha_fin;
            $tarea->prioridad = $request->prioridad;
            $tarea->estado = $request->estado;
            $tarea->save();

            return redirect()->route('tareas.index')
                ->with('success', '✅ Tarea actualizada exitosamente');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar la tarea: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function destroy($id)
    {
        $tarea = Tarea::where('id_tarea', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();

        try {
            $titulo = $tarea->titulo;
            $tarea->delete();

            return redirect()->route('tareas.index')
                ->with('success', "🗑️ Tarea '{$titulo}' eliminada exitosamente");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar la tarea: ' . $e->getMessage()]);
        }
    }

    public function completar($id)
    {
        $tarea = Tarea::where('id_tarea', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->firstOrFail();

        try {
            if ($tarea->estado === 'completada') {
                $tarea->estado = 'pendiente';
                $mensaje = '⏳ Tarea marcada como pendiente';
            } else {
                $tarea->estado = 'completada';
                $mensaje = '✅ ¡Tarea completada! Buen trabajo';
            }
            
            $tarea->save();

            return response()->json([
                'success' => true,
                'estado' => $tarea->estado,
                'mensaje' => $mensaje
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al actualizar la tarea'
            ], 500);
        }
    }

    public function calendario()
    {
        $tareas = Auth::user()->tareas()
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha_fin')
            ->get()
            ->groupBy(function($tarea) {
                return Carbon::parse($tarea->fecha_fin)->format('Y-m-d');
            });

        return view('tareas.calendario', compact('tareas'));
    }

    public function vencidas()
    {
        $tareas = Auth::user()->tareas()
            ->where('estado', 'pendiente')
            ->whereDate('fecha_fin', '<', now())
            ->orderBy('fecha_fin', 'desc')
            ->paginate(10);

        return view('tareas.vencidas', compact('tareas'));
    }
}