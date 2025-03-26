<?php

use App\Controllers\AgendamentoController;
use App\Controllers\AtividadeController;
use App\Controllers\AuthController;
use App\Controllers\CadastroController;
use App\Controllers\DisciplinaController;
use App\Controllers\PeriodoController;
use App\Controllers\RoleController;
use App\Controllers\UsuarioController;
use App\Enums\Permission;
use App\Http\Router;
use App\Middlewares\AcessoRestrito;
use App\Middlewares\NaoPodeEditarAdminOuSiMesmo;
use App\Middlewares\Visitante;

// página inicial
Router::redirect('/', '/agendamentos');

// autenticação
Router::get(
    uri: '/login',
    action: [AuthController::class, 'index'],
    middlewares: [
        Visitante::class,
    ]
);

Router::post(
    uri: '/login',
    action: [AuthController::class, 'login'],
    middlewares: [
        Visitante::class,
    ]
);

Router::get(
    uri: '/logout',
    action: [AuthController::class, 'logout']
);

// cadastro
Router::get(
    uri: '/cadastro',
    action: [CadastroController::class, 'cadastrar'],
    middlewares: [
        Visitante::class,
    ]
);

Router::post(
    uri: '/cadastro',
    action: [CadastroController::class, 'salvar'],
    middlewares: [
        Visitante::class,
    ]
);

Router::get(
    uri: '/cadastro/editar',
    action: [CadastroController::class, 'editar'],
    middlewares: [
        AcessoRestrito::class,
    ]
);

Router::put(
    uri: '/cadastro/editar',
    action: [CadastroController::class, 'atualizar'],
    middlewares: [
        AcessoRestrito::class,
    ]
);

// usuários
Router::get(
    uri: '/usuarios',
    action: [UsuarioController::class, 'index'],
    middlewares: [
        AcessoRestrito::class,
        Permission::VER_USUARIOS,
    ]
);

Router::get(
    uri: '/usuarios/cadastrar',
    action: [UsuarioController::class, 'cadastrar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_USUARIOS,
    ]
);

Router::post(
    uri: '/usuarios',
    action: [UsuarioController::class, 'salvar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_USUARIOS,
    ]
);

Router::get(
    uri: '/usuarios/{usuario}/editar',
    action: [UsuarioController::class, 'editar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_USUARIOS,
        NaoPodeEditarAdminOuSiMesmo::class,
    ]
);

Router::put(
    uri: '/usuarios/{usuario}',
    action: [UsuarioController::class, 'atualizar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_USUARIOS,
        NaoPodeEditarAdminOuSiMesmo::class,
    ]
);

Router::get(
    uri: '/agendamentos',
    action: [AgendamentoController::class, 'index']
);

Router::get(
    uri: '/agendamentos/cadastrar',
    action: [AgendamentoController::class, 'cadastrar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::VER_AGENDAMENTOS,
    ]
);

Router::post(
    uri: '/agendamentos',
    action: [AgendamentoController::class, 'salvar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_AGENDAMENTOS,
    ]
);

Router::get(
    uri: '/agendamentos/{agendamento}',
    action: [AgendamentoController::class, 'ver'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_AGENDAMENTOS,
    ]
);

Router::get(
    uri: '/agendamentos/{agendamento}/editar',
    action: [AgendamentoController::class, 'editar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_AGENDAMENTOS,
    ]
);

Router::put(
    uri: '/agendamentos/{agendamento}',
    action: [AgendamentoController::class, 'atualizar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_AGENDAMENTOS,
    ]
);

Router::delete(
    uri: '/agendamentos/{agendamento}',
    action: [AgendamentoController::class, 'excluir'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EXCLUIR_AGENDAMENTOS,
    ]
);

// períodos
Router::get(
    uri: '/periodos',
    action: [PeriodoController::class, 'index'],
    middlewares: [
        AcessoRestrito::class,
        Permission::VER_PERIODOS,
    ]
);

Router::get(
    uri: '/periodos/cadastrar',
    action: [PeriodoController::class, 'cadastrar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_PERIODOS,
    ]
);

Router::post(
    uri: '/periodos',
    action: [PeriodoController::class, 'salvar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_PERIODOS,
    ]
);

Router::get(
    uri: '/periodos/{periodo}/editar',
    action: [PeriodoController::class, 'editar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_PERIODOS,
    ]
);

Router::put(
    uri: '/periodos/{periodo}',
    action: [PeriodoController::class, 'atualizar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_PERIODOS,
    ]
);

Router::delete(
    uri: '/periodos/{periodo}',
    action: [PeriodoController::class, 'excluir'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EXCLUIR_PERIODOS,
    ]
);

// disciplinas
Router::get(
    uri: '/disciplinas',
    action: [DisciplinaController::class, 'index'],
    middlewares: [
        AcessoRestrito::class,
        Permission::VER_DISCIPLINAS,
    ]
);

Router::get(
    uri: '/disciplinas/cadastrar',
    action: [DisciplinaController::class, 'cadastrar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_DISCIPLINAS,
    ]
);

Router::post(
    uri: '/disciplinas',
    action: [DisciplinaController::class, 'salvar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_DISCIPLINAS,
    ]
);

Router::get(
    uri: '/disciplinas/{disciplina}/editar',
    action: [DisciplinaController::class, 'editar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_DISCIPLINAS,
    ]
);

Router::put(
    uri: '/disciplinas/{disciplina}',
    action: [DisciplinaController::class, 'atualizar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_DISCIPLINAS,
    ]
);

Router::delete(
    uri: '/disciplinas/{disciplina}',
    action: [DisciplinaController::class, 'excluir'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EXCLUIR_DISCIPLINAS,
    ]
);

// atividades
Router::get(
    uri: '/atividades',
    action: [AtividadeController::class, 'index'],
    middlewares: [
        AcessoRestrito::class,
        Permission::VER_ATIVIDADES,
    ]
);

Router::get(
    uri: '/atividades/cadastrar',
    action: [AtividadeController::class, 'cadastrar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_ATIVIDADES,
    ]
);

Router::post(
    uri: '/atividades',
    action: [AtividadeController::class, 'salvar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::CADASTRAR_ATIVIDADES,
    ]
);

Router::get(
    uri: '/atividades/{atividade}/editar',
    action: [AtividadeController::class, 'editar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_ATIVIDADES,
    ]
);

Router::put(
    uri: '/atividades/{atividade}',
    action: [AtividadeController::class, 'atualizar'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EDITAR_ATIVIDADES,
    ]
);

Router::delete(
    uri: '/atividades/{atividade}',
    action: [AtividadeController::class, 'excluir'],
    middlewares: [
        AcessoRestrito::class,
        Permission::EXCLUIR_ATIVIDADES,
    ]
);

// roles
Router::get(
    uri: '/roles/{role}/permissions',
    action: [RoleController::class, 'permissions'],
    middlewares: [
        AcessoRestrito::class,
        Permission::ATRIBUIR_PERMISSOES,
    ]
);
