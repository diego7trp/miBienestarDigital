<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consejo extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'contenido', 'especialista_id'];

    // Relación: un consejo pertenece a un especialista
    public function especialista()
    {
        return $this->belongsTo(Especialista::class);
    }
}

