<?php

use App\Controllers\AgendamentoController;
use App\Controllers\AtividadeController;
use App\Controllers\AuthController;
use App\Controllers\CadastroController;
use App\Controllers\DisciplinaController;
use App\Controllers\PeriodoController;
use App\Controllers\UsuarioController;
use App\Http\Router;
use App\Middlewares\AcessoRestrito;
use App\Middlewares\RequerRoleAdministrador;
use App\Middlewares\RequerRoleContribuidor;
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
Router::get('/cadastro/editar', [CadastroController::class, 'editar'], [AcessoRestrito::class]);
Router::put('/cadastro/editar', [CadastroController::class, 'atualizar'], [AcessoRestrito::class]);

// usuários
Router::get('/usuarios', [UsuarioController::class, 'index'], [AcessoRestrito::class, RequerRoleAdministrador::class]);
Router::get('/usuarios/cadastrar', [UsuarioController::class, 'cadastrar'], [AcessoRestrito::class, RequerRoleAdministrador::class]);
Router::post('/usuarios', [UsuarioController::class, 'salvar'], [AcessoRestrito::class, RequerRoleAdministrador::class]);
Router::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'editar'], [AcessoRestrito::class, RequerRoleAdministrador::class]);
Router::put('/usuarios/{usuario}', [UsuarioController::class, 'atualizar'], [AcessoRestrito::class, RequerRoleAdministrador::class]);

// agendamentos
Router::get('/agendamentos', [AgendamentoController::class, 'index']);
Router::get('/agendamentos/cadastrar', [AgendamentoController::class, 'cadastrar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::post('/agendamentos', [AgendamentoController::class, 'salvar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::get('/agendamentos/{agendamento}', [AgendamentoController::class, 'ver'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::get('/agendamentos/{periodo}/editar', [AgendamentoController::class, 'editar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::put('/agendamentos/{periodo}', [AgendamentoController::class, 'atualizar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::delete('/agendamentos/{periodo}', [AgendamentoController::class, 'excluir'], [AcessoRestrito::class, RequerRoleContribuidor::class]);

// períodos
Router::get('/periodos', [PeriodoController::class, 'index'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::get('/periodos/cadastrar', [PeriodoController::class, 'cadastrar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::post('/periodos', [PeriodoController::class, 'salvar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::get('/periodos/{periodo}/editar', [PeriodoController::class, 'editar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::put('/periodos/{periodo}', [PeriodoController::class, 'atualizar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::delete('/periodos/{periodo}', [PeriodoController::class, 'excluir'], [AcessoRestrito::class, RequerRoleContribuidor::class]);

// disciplinas
Router::get('/disciplinas', [DisciplinaController::class, 'index'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::get('/disciplinas/cadastrar', [DisciplinaController::class, 'cadastrar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::post('/disciplinas', [DisciplinaController::class, 'salvar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::get('/disciplinas/{atividade}/editar', [DisciplinaController::class, 'editar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::put('/disciplinas/{atividade}', [DisciplinaController::class, 'atualizar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::delete('/disciplinas/{atividade}', [DisciplinaController::class, 'excluir'], [AcessoRestrito::class, RequerRoleContribuidor::class]);

// atividades
Router::get('/atividades', [AtividadeController::class, 'index'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::get('/atividades/cadastrar', [AtividadeController::class, 'cadastrar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::post('/atividades', [AtividadeController::class, 'salvar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::get('/atividades/{atividade}/editar', [AtividadeController::class, 'editar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::put('/atividades/{atividade}', [AtividadeController::class, 'atualizar'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
Router::delete('/atividades/{atividade}', [AtividadeController::class, 'excluir'], [AcessoRestrito::class, RequerRoleContribuidor::class]);
