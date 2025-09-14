<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $table = 'Meta';
    protected $primaryKey = 'id_meta';
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'objetivo',
        'id_usuario',
        'fecha_limite',
        // ...
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
