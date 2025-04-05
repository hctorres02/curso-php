<?php

use App\Controllers\AgendamentoController;
use App\Controllers\AtividadeController;
use App\Controllers\AuthController;
use App\Controllers\CadastroController;
use App\Controllers\DisciplinaController;
use App\Controllers\LogController;
use App\Controllers\PeriodoController;
use App\Controllers\RoleController;
use App\Controllers\UsuarioController;
use App\Enums\Permission;
use App\Http\Router;
use App\Middlewares\AcessoRestrito;
use App\Middlewares\NaoPodeEditarAdminOuSiMesmo;
use App\Middlewares\RequerRoleAdministrador;
use App\Middlewares\Visitante;

// página inicial
Router::redirect('/', '/agendamentos', name: 'home');

// autenticação
Router::get(
    name: 'login',
    uri: '/login',
    action: [AuthController::class, 'index'],
    middlewares: [
        Visitante::class,
    ]
);

Router::post(
    name: 'submit_login',
    uri: '/login',
    action: [AuthController::class, 'login'],
    middlewares: [
        Visitante::class,
    ]
);

Router::get(
    name: 'logout',
    uri: '/logout',
    action: [AuthController::class, 'logout']
);

// cadastro
Router::get(
    name: 'cadastro',
    uri: '/cadastro',
    action: [CadastroController::class, 'cadastrar'],
    middlewares: [
        Visitante::class,
    ]
);

Router::post(
    name: 'salvar_cadastro',
    uri: '/cadastro',
    action: [CadastroController::class, 'salvar'],
    middlewares: [
        Visitante::class,
    ]
);

Router::get(
    name: 'editar_cadastro',
    uri: '/cadastro/editar',
    action: [CadastroController::class, 'editar'],
    middlewares: [
        AcessoRestrito::class,
    ]
);

Router::put(
    name: 'atualizar_cadastro',
    uri: '/cadastro/editar',
    action: [CadastroController::class, 'atualizar'],
    middlewares: [
        AcessoRestrito::class,
    ]
);

// usuários
Router::get(
    name: 'usuarios',
    uri: '/usuarios',
    action: [UsuarioController::class, 'index'],
    middlewares: [
        AcessoRestrito::class,
        Permission::VER_USUARIOS,
    ]
);

Router::get(
    name: 'cadastrar_usuario',
    uri: '/usuarios/cadastrar',
    action: [UsuarioController::class, 'cadastrar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_USUARIOS,
    ]
);

Router::post(
    name: 'salvar_usuario',
    uri: '/usuarios',
    action: [UsuarioController::class, 'salvar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_USUARIOS,
    ]
);

Router::get(
    name: 'editar_usuario',
    uri: '/usuarios/{usuario}/editar',
    action: [UsuarioController::class, 'editar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_USUARIOS,
        NaoPodeEditarAdminOuSiMesmo::class,
    ]
);

Router::put(
    name: 'atualizar_usuario',
    uri: '/usuarios/{usuario}',
    action: [UsuarioController::class, 'atualizar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_USUARIOS,
        NaoPodeEditarAdminOuSiMesmo::class,
    ]
);

Router::get(
    name: 'agendamentos',
    uri: '/agendamentos',
    action: [AgendamentoController::class, 'index']
);

Router::get(
    name: 'cadastrar_agendamento',
    uri: '/agendamentos/cadastrar',
    action: [AgendamentoController::class, 'cadastrar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::VER_AGENDAMENTOS,
    ]
);

Router::post(
    name: 'salvar_agendamento',
    uri: '/agendamentos',
    action: [AgendamentoController::class, 'salvar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_AGENDAMENTOS,
    ]
);

Router::get(
    name: 'ver_agendamento',
    uri: '/agendamentos/{agendamento}',
    action: [AgendamentoController::class, 'ver'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_AGENDAMENTOS,
    ]
);

Router::get(
    name: 'editar_agendamento',
    uri: '/agendamentos/{agendamento}/editar',
    action: [AgendamentoController::class, 'editar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_AGENDAMENTOS,
    ]
);

Router::put(
    name: 'atualizar_agendamento',
    uri: '/agendamentos/{agendamento}',
    action: [AgendamentoController::class, 'atualizar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_AGENDAMENTOS,
    ]
);

Router::delete(
    name: 'excluir_agendamento',
    uri: '/agendamentos/{agendamento}',
    action: [AgendamentoController::class, 'excluir'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EXCLUIR_AGENDAMENTOS,
    ]
);

// períodos
Router::get(
    name: 'periodos',
    uri: '/periodos',
    action: [PeriodoController::class, 'index'],
    middlewares: [
        AcessoRestrito::class,
        Permission::VER_PERIODOS,
    ]
);

Router::get(
    name: 'cadastrar_periodo',
    uri: '/periodos/cadastrar',
    action: [PeriodoController::class, 'cadastrar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_PERIODOS,
    ]
);

Router::post(
    name: 'salvar_periodo',
    uri: '/periodos',
    action: [PeriodoController::class, 'salvar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_PERIODOS,
    ]
);

Router::get(
    name: 'editar_periodo',
    uri: '/periodos/{periodo}/editar',
    action: [PeriodoController::class, 'editar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_PERIODOS,
    ]
);

Router::put(
    name: 'atualizar_periodo',
    uri: '/periodos/{periodo}',
    action: [PeriodoController::class, 'atualizar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_PERIODOS,
    ]
);

Router::delete(
    name: 'excluir_periodo',
    uri: '/periodos/{periodo}',
    action: [PeriodoController::class, 'excluir'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EXCLUIR_PERIODOS,
    ]
);

// disciplinas
Router::get(
    name: 'disciplinas',
    uri: '/disciplinas',
    action: [DisciplinaController::class, 'index'],
    middlewares: [
        AcessoRestrito::class,
        Permission::VER_DISCIPLINAS,
    ]
);

Router::get(
    name: 'cadastrar_disciplina',
    uri: '/disciplinas/cadastrar',
    action: [DisciplinaController::class, 'cadastrar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_DISCIPLINAS,
    ]
);

Router::post(
    name: 'salvar_disciplina',
    uri: '/disciplinas',
    action: [DisciplinaController::class, 'salvar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_DISCIPLINAS,
    ]
);

Router::get(
    name: 'editar_disciplina',
    uri: '/disciplinas/{disciplina}/editar',
    action: [DisciplinaController::class, 'editar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_DISCIPLINAS,
    ]
);

Router::put(
    name: 'atualizar_disciplina',
    uri: '/disciplinas/{disciplina}',
    action: [DisciplinaController::class, 'atualizar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_DISCIPLINAS,
    ]
);

Router::delete(
    name: 'excluir_disciplina',
    uri: '/disciplinas/{disciplina}',
    action: [DisciplinaController::class, 'excluir'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EXCLUIR_DISCIPLINAS,
    ]
);

// atividades
Router::get(
    name: 'atividades',
    uri: '/atividades',
    action: [AtividadeController::class, 'index'],
    middlewares: [
        AcessoRestrito::class,
        Permission::VER_ATIVIDADES,
    ]
);

Router::get(
    name: 'cadastrar_atividade',
    uri: '/atividades/cadastrar',
    action: [AtividadeController::class, 'cadastrar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_ATIVIDADES,
    ]
);

Router::post(
    name: 'salvar_atividade',
    uri: '/atividades',
    action: [AtividadeController::class, 'salvar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_ATIVIDADES,
    ]
);

Router::get(
    name: 'editar_atividade',
    uri: '/atividades/{atividade}/editar',
    action: [AtividadeController::class, 'editar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_ATIVIDADES,
    ]
);

Router::put(
    name: 'atualizar_atividade',
    uri: '/atividades/{atividade}',
    action: [AtividadeController::class, 'atualizar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_ATIVIDADES,
    ]
);

Router::delete(
    name: 'excluir_atividade',
    uri: '/atividades/{atividade}',
    action: [AtividadeController::class, 'excluir'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EXCLUIR_ATIVIDADES,
    ]
);

// roles
Router::get(
    name: 'role_permissions',
    uri: '/roles/{role}/permissions',
    action: [RoleController::class, 'permissions'],
    middlewares: [
        AcessoRestrito::class,
        Permission::ATRIBUIR_PERMISSOES,
    ]
);

// logs
Router::get(
    name: 'logs',
    uri: '/logs',
    action: [LogController::class, 'index'],
    middlewares: [
        AcessoRestrito::class,
        RequerRoleAdministrador::class
    ]
);

Router::delete(
    name: 'excluir_log',
    uri: '/logs/{key}',
    action: [LogController::class, 'excluir'],
    middlewares: [
        AcessoRestrito::class,
        RequerRoleAdministrador::class
    ]
);
