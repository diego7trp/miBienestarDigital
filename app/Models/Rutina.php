<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rutina extends Model
{
    protected $table = 'Rutina';
    protected $primaryKey = 'id_rutina';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'Frecuencia', // ejemplo
        'id_usuario',
        'id_habito',
        // ...
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function habito()
    {
        return $this->belongsTo(Habito::class, 'id_habito', 'id_habito');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'id_rutina', 'id_rutina');
    }
}
