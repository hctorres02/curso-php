<?php

namespace App\Middlewares;

class ForcarLogout
{
    public function __invoke(): bool
    {
        session()->remove('usuario_id');
        session()->remove('usuario');

        return true;
    }
}
