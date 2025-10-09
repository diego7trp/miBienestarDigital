<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Especificar el nombre de la tabla
    protected $table = 'Usuario';
    
    // Especificar la clave primaria
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'fecha_nacimiento',
        'sexo',
        // Agrega otros campos que tengas
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relación con rutinas
     * Segundo parámetro: foreign key en la tabla Rutina
     * Tercer parámetro: local key en la tabla Usuario
     */
    public function rutinas()
    {
        return $this->hasMany(Rutina::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación con tareas
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación con metas
     * IMPORTANTE: Ajusta 'paciente_id' si tu columna se llama diferente
     */
    public function metas()
    {
        return $this->hasMany(Meta::class, 'id_usuario', 'id_usuario');
    }
}