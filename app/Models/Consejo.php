<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consejo extends Model
{
    protected $table = 'Consejo';
    protected $primaryKey = 'id_consejo';
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_creacion',
        'id_usuario'
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}