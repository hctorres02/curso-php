<?php

namespace App\Middlewares;

use App\Enums\Role;

class RequerRoleAdministrador
{
    public function __invoke(): bool
    {
        return hasRole(Role::ADMINISTRADOR);
    }
}
