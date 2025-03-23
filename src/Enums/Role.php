<?php

namespace App\Enums;

use App\Traits\EnumSupport;

enum Role: string
{
    use EnumSupport;

    case ADMINISTRADOR = 'administrador';
    case CONTRIBUIDOR = 'contribuidor';
    case VISITANTE = 'visitante';

    public function hasPermission(Permission|string $permission): bool
    {
        return in_array($permission->value ?? $permission, $this->permissions());
    }

    public function permissions(): array
    {
        static $cachedPermissions = [];

        $roleKey = $this->value;
        $cachedPermissions[$roleKey] ??= array_column(match ($this) {
            self::VISITANTE => [
                Permission::MANTER_PERFIL,
            ],
            self::CONTRIBUIDOR => [
                Permission::MANTER_PERFIL,
                Permission::MANTER_AGENDAMENTOS,
                Permission::MANTER_ATIVIDADES,
                Permission::MANTER_DISCIPLINAS,
                Permission::MANTER_PERIODOS,
            ],
            self::ADMINISTRADOR => [
                Permission::MANTER_PERFIL,
                Permission::MANTER_AGENDAMENTOS,
                Permission::MANTER_ATIVIDADES,
                Permission::MANTER_DISCIPLINAS,
                Permission::MANTER_PERIODOS,
                Permission::MANTER_PERMISSOES,
                Permission::MANTER_USUARIOS,
            ],
        }, 'value');

        return $cachedPermissions[$roleKey];
    }
}
