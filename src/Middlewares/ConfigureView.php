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
            'ERRORS' => $request->getErrors(),
            'CSRF_TOKEN' => $request->generateCsrfToken(),
            'CURRENT_URI' => $request->getPathInfo(),
        ]);

        return true;
    }
}
