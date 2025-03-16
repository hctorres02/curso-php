<?php

namespace App\Middlewares;

use App\Http\Request;
use App\Http\View;

class ConfigureView
{
    public function __invoke(Request $request): bool
    {
        View::addFunction('attr', attr(...));
        View::addFunction('url', url(...));
        View::addGlobals([
            'APP_LOCALE' => str_replace('_', '-', env('APP_LOCALE')),
            'CSRF_TOKEN' => session()->get('csrf_token'),
            'CURRENT_URI' => $request->getPathInfo(),
            'ERRORS' => flash()->get('err'),
            'OLD' => flash()->get('old'),
            'USUARIO' => session()->get('usuario'),
        ]);

        return true;
    }
}
