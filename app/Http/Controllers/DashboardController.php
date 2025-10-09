<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rutina;
use App\Models\Tarea;
use App\Models\Meta;
use App\Models\Consejo;
use App\Models\Habito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();
        
        // Por ahora, usar solo dashboard de paciente hasta crear los otros roles
        return $this->dashboardPaciente();
    }

    private function dashboardPaciente()
    {
        $usuario = Auth::user();
        
        // Estadísticas básicas (usando valores por defecto si no hay datos)
        $estadisticas = [
            'rutinas_total' => $usuario->rutinas ? $usuario->rutinas->count() : 0,
            'rutinas_completadas_hoy' => 0, // Simplificado por ahora
            'tareas_pendientes' => $usuario->tareas ? $usuario->tareas->where('estado', 'pendiente')->count() : 0,
            'tareas_vencidas' => 0, // Simplificado por ahora
            'metas_cumplidas' => $usuario->metas ? $usuario->metas->where('estado', 'completada')->count() : 0,
            'habitos_activos' => $usuario->habitos ? $usuario->habitos->count() : 0,
        ];

        // Rutinas pendientes (simplificado)
        $rutinasPendientes = $usuario->rutinas ? $usuario->rutinas()->limit(5)->get() : collect();

        // Tareas urgentes (simplificado)
        $tareasUrgentes = $usuario->tareas ? $usuario->tareas()->where('estado', 'pendiente')->limit(5)->get() : collect();

        // Progreso semanal (datos de ejemplo por ahora)
        $progresoSemanal = collect([
            ['dia' => 'Lun', 'porcentaje' => 80, 'fecha' => '2024-01-01'],
            ['dia' => 'Mar', 'porcentaje' => 60, 'fecha' => '2024-01-02'],
            ['dia' => 'Mié', 'porcentaje' => 90, 'fecha' => '2024-01-03'],
            ['dia' => 'Jue', 'porcentaje' => 70, 'fecha' => '2024-01-04'],
            ['dia' => 'Vie', 'porcentaje' => 85, 'fecha' => '2024-01-05'],
            ['dia' => 'Sáb', 'porcentaje' => 95, 'fecha' => '2024-01-06'],
            ['dia' => 'Dom', 'porcentaje' => 40, 'fecha' => '2024-01-07'],
        ]);

        // Últimos consejos (vacío por ahora)
        $ultimosConsejos = collect();

        return view('dashboard.paciente', compact(
            'estadisticas', 
            'rutinasPendientes', 
            'tareasUrgentes', 
            'progresoSemanal', 
            'ultimosConsejos'
        ));
    }
}
