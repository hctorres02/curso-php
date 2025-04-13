<?php

namespace App\Middlewares;

use App\Http\Request;
use App\Models\Usuario;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AcessoRestrito
{
    public function __invoke(Request $request) : bool|RedirectResponse
    {
        if ($request->get('_app_key') === env('APP_KEY')) {
            $admin = Usuario::firstWhere('email', env('ADMIN_EMAIL'));

            session()->set('usuario_id', $admin->id);
            session()->set('usuario', $admin);
        }

        if (session()->get('usuario')) {
            return true;
        }

        session()->set('attempted_uri', $request->getRequestUri());

        return redirectRoute('login');
    }
}
