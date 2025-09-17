<?php

namespace App\Policies;

use App\Models\Rutina;
use App\Models\Usuario;
use Illuminate\Auth\Access\HandlesAuthorization;

class RutinaPolicy
{
    use HandlesAuthorization;

    public function view(Usuario $user, Rutina $rutina)
    {
        return $user->id_usuario === $rutina->id_usuario;
    }

    public function update(Usuario $user, Rutina $rutina)
    {
        return $user->id_usuario === $rutina->id_usuario;
    }

    public function delete(Usuario $user, Rutina $rutina)
    {
        return $user->id_usuario === $rutina->id_usuario;
    }
}