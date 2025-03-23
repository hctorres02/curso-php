<?php

namespace App\Enums;

use App\Traits\EnumSupport;

enum Permission: string
{
    use EnumSupport;

    case MANTER_PERFIL = 'manter_perfil';
    case MANTER_AGENDAMENTOS = 'manter_agendamentos';
    case MANTER_DISCIPLINAS = 'manter_disciplinas';
    case MANTER_ATIVIDADES = 'manter_atividades';
    case MANTER_PERIODOS = 'manter_periodos';
    case MANTER_USUARIOS = 'manter_usuarios';
    case MANTER_PERMISSOES = 'manter_permissoes';
    case MANTER_HORARIOS = 'manter_horarios';
}
