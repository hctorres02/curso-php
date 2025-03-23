<?php

namespace App\Middlewares;

use App\Models\Usuario;

class NaoPodeEditarAdminOuSiMesmo
{
    public function __invoke(Usuario $usuario): bool
    {
        return $usuario->id !== 1 && $usuario->isNot(session()->get('usuario'));
    }
}
