<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'Usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    // columnas asignables (ajusta a tu esquema)
    protected $fillable = [
        'nombre',
        'correo',
        'password_hash',
        'id_rol',
        // ...
    ];

    // esconder por ejemplo el hash de la contraseña
    protected $hidden = [
        'password_hash',
    ];

    // si tu columna de contraseña se llama password_hash
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function habitos()
    {
        return $this->hasMany(Habito::class, 'id_usuario', 'id_usuario');
    }

    public function rutinas()
    {
        return $this->hasMany(Rutina::class, 'id_usuario', 'id_usuario');
    }
}
