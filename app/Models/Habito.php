<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habito extends Model
{
    protected $table = 'Habito';
    protected $primaryKey = 'id_habito';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_usuario',
        // ...
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function rutinas()
    {
        return $this->hasMany(Rutina::class, 'id_habito', 'id_habito');
    }

    public function consejos()
    {
        return $this->hasMany(Consejo::class, 'id_habito', 'id_habito');
    }
}
