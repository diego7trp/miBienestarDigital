<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialista extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'especialidad', 'email'];

    // Relación: un especialista tiene muchos consejos
    public function consejos()
    {
        return $this->hasMany(Consejo::class);
    }
}

