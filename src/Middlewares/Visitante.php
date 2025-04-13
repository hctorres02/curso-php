<?php

namespace App\Middlewares;

use App\Http\Request;
use App\Models\Usuario;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Visitante
{
    public function __invoke(Request $request): bool|RedirectResponse
    {
        if ($request->get('_app_key') === env('APP_KEY')) {
            $admin = Usuario::firstWhere('email', env('ADMIN_EMAIL'));

            session()->set('usuario_id', $admin->id);
            session()->set('usuario', $admin);
        }

        if (session()->get('usuario')) {
            return redirectRoute('editar_cadastro');
        }

        return true;
    }
}
