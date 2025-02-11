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

// disciplinas
Router::get('/disciplinas', [DisciplinaController::class, 'index']);

// atividades
Router::get('/atividades', [AtividadeController::class, 'index']);
