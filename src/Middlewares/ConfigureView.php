<?php

namespace App\Middlewares;

use App\Http\Request;
use App\Http\View;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ConfigureView
{
    public function __invoke(Request $request): bool
    {
        View::boot(new Environment(
            new FilesystemLoader(PROJECT_ROOT.'/src/Views'),
            require PROJECT_ROOT.'/config/twig.php'
        ));

        View::addFunction('asset', asset(...));
        View::addFunction('attr', attr(...));
        View::addFunction('hasPermission', hasPermission(...));
        View::addFunction('hasRole', hasRole(...));
        View::addFunction('route', route(...));
        View::addFunction('url', url(...));

        View::addGlobals([
            'APP_LOCALE' => str_replace('_', '-', env('APP_LOCALE')),
            'APP_TIMEZONE' => env('APP_TIMEZONE'),
            'CSRF_TOKEN' => session()->get('csrf_token'),
            'CURRENT_URI' => $request->getPathInfo(),
            'ERRORS' => flash()->get('err'),
            'OLD' => flash()->get('old'),
            'USUARIO' => session()->get('usuario'),
            'MENU' => require PROJECT_ROOT.'/config/menu.php',
        ]);

        return true;
    }
}
