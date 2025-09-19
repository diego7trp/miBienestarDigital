<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'Usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'correo',
        'password_hash',
        'id_rol',
        'genero',
        'edad',
        'peso',
        'altura',
        'telefono',
        'condiciones_medicas',
        'perfil_completado'
    ];

    protected $hidden = [
        'password_hash',
        'remember_token'
    ];

    protected $casts = [
        'perfil_completado' => 'boolean',
        'peso' => 'decimal:2'
    ];

    // Métodos de autenticación (mantener los existentes)
    public function getAuthIdentifierName()
    {
        return 'id_usuario';
    }

    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    // Mutador para hashear contraseña automáticamente
    public function setPasswordHashAttribute($value)
    {
        $this->attributes['password_hash'] = Hash::make($value);
    }

    // Relaciones existentes
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function rutinas()
    {
        return $this->hasMany(Rutina::class, 'id_usuario', 'id_usuario');
    }

    public function habitos()
    {
        return $this->hasMany(Habito::class, 'id_usuario', 'id_usuario');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'id_usuario', 'id_usuario');
    }

    public function metas()
    {
        return $this->hasMany(Meta::class, 'id_usuario', 'id_usuario');
    }

    // Métodos helper
    public function esPaciente()
    {
        return $this->id_rol === 1;
    }

    public function esAdministrador()
    {
        return $this->id_rol === 2;
    }

    public function esEspecialista()
    {
        return $this->id_rol === 3;
    }

    public function tienePerfilCompleto()
    {
        return $this->perfil_completado;
    }

    // Calcular IMC
    public function getImcAttribute()
    {
        if ($this->peso && $this->altura) {
            $alturaMetros = $this->altura / 100;
            return round($this->peso / ($alturaMetros * $alturaMetros), 2);
        }
        return null;
    }

    // Obtener categoría de IMC
    public function getCategoriaImcAttribute()
    {
        $imc = $this->imc;
        if (!$imc) return null;

        if ($imc < 18.5) return 'Bajo peso';
        if ($imc < 25) return 'Peso normal';
        if ($imc < 30) return 'Sobrepeso';
        return 'Obesidad';
    }
}