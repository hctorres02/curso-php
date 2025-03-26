<?php

namespace App\Enums;

use App\Traits\EnumSupport;

enum Permission: string
{
    use EnumSupport;

    // AGENDAMENTOS
    case VER_AGENDAMENTOS = 'ver_agendamentos';
    case CADASTRAR_AGENDAMENTOS = 'cadastrar_agendamentos';
    case EDITAR_AGENDAMENTOS = 'editar_agendamentos';
    case EXCLUIR_AGENDAMENTOS = 'excluir_agendamentos';

    // DISCIPLINAS
    case VER_DISCIPLINAS = 'ver_disciplinas';
    case CADASTRAR_DISCIPLINAS = 'cadastrar_disciplinas';
    case EDITAR_DISCIPLINAS = 'editar_disciplinas';
    case EXCLUIR_DISCIPLINAS = 'excluir_disciplinas';

    // ATIVIDADES
    case VER_ATIVIDADES = 'ver_atividades';
    case CADASTRAR_ATIVIDADES = 'cadastrar_atividades';
    case EDITAR_ATIVIDADES = 'editar_atividades';
    case EXCLUIR_ATIVIDADES = 'excluir_atividades';

    // PERÍODOS
    case VER_PERIODOS = 'ver_periodos';
    case CADASTRAR_PERIODOS = 'cadastrar_periodos';
    case EDITAR_PERIODOS = 'editar_periodos';
    case EXCLUIR_PERIODOS = 'excluir_periodos';

    // USUÁRIOS
    case VER_USUARIOS = 'ver_usuarios';
    case CADASTRAR_USUARIOS = 'cadastrar_usuarios';
    case EDITAR_USUARIOS = 'editar_usuarios';
    case EXCLUIR_USUARIOS = 'excluir_usuarios';

    // ACL
    case ATRIBUIR_PERMISSOES = 'atribuir_permissoes';
    case ATRIBUIR_ROLE = 'atribuir_role';
}
