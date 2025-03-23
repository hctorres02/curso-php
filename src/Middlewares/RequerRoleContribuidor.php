<?php

namespace App\Middlewares;

use App\Enums\Role;

class RequerRoleContribuidor
{
    public function __invoke(): bool
    {
        return hasRole(Role::ADMINISTRADOR, Role::CONTRIBUIDOR);
    }
}
