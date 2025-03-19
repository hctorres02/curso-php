<?php

namespace App\Middlewares;

use App\Http\Request;
use Illuminate\Database\Capsule\Manager as DB;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class StartSession
{
    public function __invoke(Request $request): bool
    {
        $handler = new PdoSessionHandler(DB::connection()->getPdo());
        $storage = new NativeSessionStorage(handler: $handler);
        $session = new Session($storage);

        $request->setSession($session);
        $request->setUsuario($session->get('usuario_id'));
        $request->generateCsrfToken();

        return true;
    }
}
