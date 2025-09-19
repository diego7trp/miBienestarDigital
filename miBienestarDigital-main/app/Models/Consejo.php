<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consejo extends Model
{
    protected $table = 'Consejo';
    protected $primaryKey = 'id_consejo';
    public $timestamps = false;

    protected $fillable = [
        'texto',
        'id_habito',
        // ...
    ];

    public function habito()
    {
        return $this->belongsTo(Habito::class, 'id_habito', 'id_habito');
    }
}
