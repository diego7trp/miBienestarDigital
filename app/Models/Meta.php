<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;

    protected $table = 'metas';

    protected $fillable = [
        'id_usuario',  // Cambiar de paciente_id
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'progreso',
        'categoria',
        'prioridad'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'progreso' => 'integer'
    ];

    public function paciente()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function scopeActivas($query)
    {
        return $query->whereIn('estado', ['pendiente', 'en_progreso']);
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    public function estaVencida()
    {
        return $this->fecha_fin < now() && $this->estado !== 'completada';
    }

    public function getColorEstado()
    {
        return match($this->estado) {
            'pendiente' => 'secondary',
            'en_progreso' => 'primary',
            'completada' => 'success',
            'cancelada' => 'danger',
            default => 'secondary'
        };
    }

    public function getColorPrioridad()
    {
        return match($this->prioridad) {
            'baja' => 'info',
            'media' => 'warning',
            'alta' => 'danger',
            default => 'secondary'
        };
    }
}