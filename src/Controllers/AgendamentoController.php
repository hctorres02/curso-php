<?php

namespace App\Controllers;

use App\Http\View;
use App\Models\Agendamento;

class AgendamentoController
{
    public function index()
    {
        return View::render('agendamentos/index', [
            'agendamentos' => Agendamento::query()->get(),
        ]);
    }

    public function ver(Agendamento $agendamento)
    {
        return View::render('agendamentos/ver', [
            'agendamento' => $agendamento,
        ]);
    }
}
