<?php

namespace App\Middlewares;

use Symfony\Component\HttpFoundation\RedirectResponse;

class Visitante
{
    public function __invoke(): bool|RedirectResponse
    {
        if (session()->get('usuario')) {
            return redirect(route('editar_cadastro'));
        }

        return true;
    }
}
