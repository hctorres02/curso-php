<?php

use App\Http\Router;

// página inicial
Router::redirect('/', '/agendamentos');

// agendamentos
Router::get('/agendamentos', fn () => 'Agendamentos');
