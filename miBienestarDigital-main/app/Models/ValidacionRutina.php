<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidacionRutina extends Model
{
    protected $table = 'validacion_rutina';
    protected $primaryKey = 'id_validacion_rutina';
    public $timestamps = false;

    protected $fillable = [
        'id_rutina',
        'fecha',
        'completada'
    ];

    protected $casts = [
        'completada' => 'boolean',
        'fecha' => 'datetime'
    ];

    public function rutina()
    {
        return $this->belongsTo(Rutina::class, 'id_rutina', 'id_rutina');
    }
}