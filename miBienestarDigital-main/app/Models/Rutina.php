<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rutina extends Model
{
    use HasFactory;

    protected $table = 'Rutina';
    protected $primaryKey = 'id_rutina';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombre',
        'descripcion',
        'Frecuencia',
        'Horario',
        'notificaciones',
        'id_habito'
    ];

    protected $casts = [
        'notificaciones' => 'boolean',
        'Horario' => 'datetime:H:i',
        'fecha_creacion' => 'datetime'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function habito()
    {
        return $this->belongsTo(Habito::class, 'id_habito', 'id_habito');
    }

    public function validaciones()
    {
        return $this->hasMany(ValidacionRutina::class, 'id_rutina', 'id_rutina');
    }
}