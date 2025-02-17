<?php

use App\Controllers\AgendamentoController;
use App\Controllers\AtividadeController;
use App\Controllers\DisciplinaController;
use App\Controllers\PeriodoController;
use App\Http\Router;

// página inicial
Router::redirect('/', '/agendamentos');

// agendamentos
Router::get('/agendamentos', [AgendamentoController::class, 'index']);
Router::get('/agendamentos/{agendamento}', [AgendamentoController::class, 'ver']);

// períodos
Router::get('/periodos', [PeriodoController::class, 'index']);
Router::get('/periodos/cadastrar', [PeriodoController::class, 'cadastrar']);
Router::post('/periodos', [PeriodoController::class, 'salvar']);
Router::get('/periodos/{periodo}/editar', [PeriodoController::class, 'editar']);
Router::put('/periodos/{periodo}', [PeriodoController::class, 'atualizar']);
Router::delete('/periodos/{periodo}', [PeriodoController::class, 'excluir']);

// disciplinas
Router::get('/disciplinas', [DisciplinaController::class, 'index']);
Router::get('/disciplinas/cadastrar', [DisciplinaController::class, 'cadastrar']);
Router::post('/disciplinas', [DisciplinaController::class, 'salvar']);
Router::get('/disciplinas/{atividade}/editar', [DisciplinaController::class, 'editar']);
Router::put('/disciplinas/{atividade}', [DisciplinaController::class, 'atualizar']);
Router::delete('/disciplinas/{atividade}', [DisciplinaController::class, 'excluir']);

// atividades
Router::get('/atividades', [AtividadeController::class, 'index']);
Router::get('/atividades/cadastrar', [AtividadeController::class, 'cadastrar']);
Router::post('/atividades', [AtividadeController::class, 'salvar']);
Router::get('/atividades/{atividade}/editar', [AtividadeController::class, 'editar']);
Router::put('/atividades/{atividade}', [AtividadeController::class, 'atualizar']);
Router::delete('/atividades/{atividade}', [AtividadeController::class, 'excluir']);
