<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}