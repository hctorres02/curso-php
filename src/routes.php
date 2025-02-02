<?php

use App\Controllers\AgendamentoController;
use App\Http\Router;

// página inicial
Router::redirect('/', '/agendamentos');

// agendamentos
Router::get('/agendamentos', [AgendamentoController::class, 'index']);
Router::get('/agendamentos/{agendamento}', [AgendamentoController::class, 'ver']);
