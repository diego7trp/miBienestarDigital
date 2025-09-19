<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tarea extends Model
{
    protected $table = 'Tarea';
    protected $primaryKey = 'id_tarea';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'titulo',
        'descripcion',
        'fecha_creacion',
        'fecha_fin',
        'prioridad',
        'estado'
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_fin' => 'date'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // Accessors y Mutators
    public function getEsVencidaAttribute()
    {
        return $this->estado === 'pendiente' && Carbon::parse($this->fecha_fin)->isPast();
    }

    public function getEsHoyAttribute()
    {
        return Carbon::parse($this->fecha_fin)->isToday();
    }

    public function getEsProximaAttribute()
    {
        $fechaFin = Carbon::parse($this->fecha_fin);
        return $this->estado === 'pendiente' && $fechaFin->isFuture() && $fechaFin->diffInDays(now()) <= 3;
    }

    public function getDiasRestantesAttribute()
    {
        return Carbon::now()->diffInDays(Carbon::parse($this->fecha_fin), false);
    }

    public function getFechaFormateadaAttribute()
    {
        return Carbon::parse($this->fecha_fin)->format('d/m/Y');
    }

    public function getFechaRelativaAttribute()
    {
        return Carbon::parse($this->fecha_fin)->diffForHumans();
    }

    public function getColorPrioridadAttribute()
    {
        return match($this->prioridad) {
            'alta' => 'danger',
            'media' => 'warning', 
            'baja' => 'info',
            default => 'secondary'
        };
    }

    public function getIconoPrioridadAttribute()
    {
        return match($this->prioridad) {
            'alta' => 'fas fa-exclamation-triangle',
            'media' => 'fas fa-exclamation-circle',
            'baja' => 'fas fa-info-circle',
            default => 'fas fa-circle'
        };
    }

    public function getColorEstadoAttribute()
    {
        return match($this->estado) {
            'completada' => 'success',
            'pendiente' => $this->es_vencida ? 'danger' : 'primary',
            'cancelada' => 'secondary',
            default => 'secondary'
        };
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    public function scopeVencidas($query)
    {
        return $query->where('estado', 'pendiente')
                    ->whereDate('fecha_fin', '<', now());
    }

    public function scopeHoy($query)
    {
        return $query->where('estado', 'pendiente')
                    ->whereDate('fecha_fin', now());
    }

    public function scopeProximas($query)
    {
        return $query->where('estado', 'pendiente')
                    ->whereDate('fecha_fin', '>', now())
                    ->whereDate('fecha_fin', '<=', now()->addDays(3));
    }

    public function scopePorPrioridad($query)
    {
        return $query->orderByRaw("
            CASE prioridad 
                WHEN 'alta' THEN 1 
                WHEN 'media' THEN 2 
                WHEN 'baja' THEN 3 
                ELSE 4 
            END
        ");
    }
}