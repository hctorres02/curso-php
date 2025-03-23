<?php

use App\Controllers\AgendamentoController;
use App\Controllers\AtividadeController;
use App\Controllers\AuthController;
use App\Controllers\CadastroController;
use App\Controllers\DisciplinaController;
use App\Controllers\PeriodoController;
use App\Controllers\UsuarioController;
use App\Enums\Permission;
use App\Http\Router;
use App\Middlewares\AcessoRestrito;
use App\Middlewares\Visitante;

// página inicial
Router::redirect('/', '/agendamentos');

// autenticação
Router::get('/login', [AuthController::class, 'index'], [Visitante::class]);
Router::post('/login', [AuthController::class, 'login'], [Visitante::class]);
Router::get('/logout', [AuthController::class, 'logout']);

// cadastro
Router::get('/cadastro', [CadastroController::class, 'cadastrar'], [Visitante::class]);
Router::post('/cadastro', [CadastroController::class, 'salvar'], [Visitante::class]);
Router::get('/cadastro/editar', [CadastroController::class, 'editar'], [AcessoRestrito::class, Permission::MANTER_PERFIL]);
Router::put('/cadastro/editar', [CadastroController::class, 'atualizar'], [AcessoRestrito::class, Permission::MANTER_PERFIL]);

// usuários
Router::get('/usuarios', [UsuarioController::class, 'index'], [AcessoRestrito::class, Permission::MANTER_USUARIOS]);
Router::get('/usuarios/cadastrar', [UsuarioController::class, 'cadastrar'], [AcessoRestrito::class, Permission::MANTER_USUARIOS]);
Router::post('/usuarios', [UsuarioController::class, 'salvar'], [AcessoRestrito::class, Permission::MANTER_USUARIOS]);
Router::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'editar'], [AcessoRestrito::class, Permission::MANTER_USUARIOS]);
Router::put('/usuarios/{usuario}', [UsuarioController::class, 'atualizar'], [AcessoRestrito::class, Permission::MANTER_USUARIOS]);

// agendamentos
Router::get('/agendamentos', [AgendamentoController::class, 'index']);
Router::get('/agendamentos/cadastrar', [AgendamentoController::class, 'cadastrar'], [AcessoRestrito::class, Permission::MANTER_AGENDAMENTOS]);
Router::post('/agendamentos', [AgendamentoController::class, 'salvar'], [AcessoRestrito::class, Permission::MANTER_AGENDAMENTOS]);
Router::get('/agendamentos/{agendamento}', [AgendamentoController::class, 'ver'], [AcessoRestrito::class, Permission::MANTER_AGENDAMENTOS]);
Router::get('/agendamentos/{periodo}/editar', [AgendamentoController::class, 'editar'], [AcessoRestrito::class, Permission::MANTER_AGENDAMENTOS]);
Router::put('/agendamentos/{periodo}', [AgendamentoController::class, 'atualizar'], [AcessoRestrito::class, Permission::MANTER_AGENDAMENTOS]);
Router::delete('/agendamentos/{periodo}', [AgendamentoController::class, 'excluir'], [AcessoRestrito::class, Permission::MANTER_AGENDAMENTOS]);

// períodos
Router::get('/periodos', [PeriodoController::class, 'index'], [AcessoRestrito::class, Permission::MANTER_PERIODOS]);
Router::get('/periodos/cadastrar', [PeriodoController::class, 'cadastrar'], [AcessoRestrito::class, Permission::MANTER_PERIODOS]);
Router::post('/periodos', [PeriodoController::class, 'salvar'], [AcessoRestrito::class, Permission::MANTER_PERIODOS]);
Router::get('/periodos/{periodo}/editar', [PeriodoController::class, 'editar'], [AcessoRestrito::class, Permission::MANTER_PERIODOS]);
Router::put('/periodos/{periodo}', [PeriodoController::class, 'atualizar'], [AcessoRestrito::class, Permission::MANTER_PERIODOS]);
Router::delete('/periodos/{periodo}', [PeriodoController::class, 'excluir'], [AcessoRestrito::class, Permission::MANTER_PERIODOS]);

// disciplinas
Router::get('/disciplinas', [DisciplinaController::class, 'index'], [AcessoRestrito::class, Permission::MANTER_DISCIPLINAS]);
Router::get('/disciplinas/cadastrar', [DisciplinaController::class, 'cadastrar'], [AcessoRestrito::class, Permission::MANTER_DISCIPLINAS]);
Router::post('/disciplinas', [DisciplinaController::class, 'salvar'], [AcessoRestrito::class, Permission::MANTER_DISCIPLINAS]);
Router::get('/disciplinas/{atividade}/editar', [DisciplinaController::class, 'editar'], [AcessoRestrito::class, Permission::MANTER_DISCIPLINAS]);
Router::put('/disciplinas/{atividade}', [DisciplinaController::class, 'atualizar'], [AcessoRestrito::class, Permission::MANTER_DISCIPLINAS]);
Router::delete('/disciplinas/{atividade}', [DisciplinaController::class, 'excluir'], [AcessoRestrito::class, Permission::MANTER_DISCIPLINAS]);

// atividades
Router::get('/atividades', [AtividadeController::class, 'index'], [AcessoRestrito::class, Permission::MANTER_ATIVIDADES]);
Router::get('/atividades/cadastrar', [AtividadeController::class, 'cadastrar'], [AcessoRestrito::class, Permission::MANTER_ATIVIDADES]);
Router::post('/atividades', [AtividadeController::class, 'salvar'], [AcessoRestrito::class, Permission::MANTER_ATIVIDADES]);
Router::get('/atividades/{atividade}/editar', [AtividadeController::class, 'editar'], [AcessoRestrito::class, Permission::MANTER_ATIVIDADES]);
Router::put('/atividades/{atividade}', [AtividadeController::class, 'atualizar'], [AcessoRestrito::class, Permission::MANTER_ATIVIDADES]);
Router::delete('/atividades/{atividade}', [AtividadeController::class, 'excluir'], [AcessoRestrito::class, Permission::MANTER_ATIVIDADES]);
