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
            default => [],
            self::VISITANTE => [
                Permission::VER_AGENDAMENTOS,
            ],
            self::CONTRIBUIDOR => [
                Permission::VER_AGENDAMENTOS,
                Permission::CADASTRAR_AGENDAMENTOS,
                Permission::EDITAR_AGENDAMENTOS,
                Permission::EXCLUIR_AGENDAMENTOS,

                Permission::VER_ATIVIDADES,
                Permission::CADASTRAR_ATIVIDADES,
                Permission::EDITAR_ATIVIDADES,
                Permission::EXCLUIR_ATIVIDADES,

                Permission::VER_DISCIPLINAS,
                Permission::CADASTRAR_DISCIPLINAS,
                Permission::EDITAR_DISCIPLINAS,
                Permission::EXCLUIR_DISCIPLINAS,

                Permission::VER_PERIODOS,
                Permission::CADASTRAR_PERIODOS,
                Permission::EDITAR_PERIODOS,
                Permission::EXCLUIR_PERIODOS,
            ],
        }, 'value');

        return $cachedPermissions[$roleKey];
    }
}
