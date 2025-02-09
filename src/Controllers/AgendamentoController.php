<?php

namespace App\Controllers;

use App\Http\View;
use App\Models\Agendamento;

class AgendamentoController
{
    public function index()
    {
        $agendamentos = Agendamento::query()
            ->previstos()
            ->oldest('data')
            ->with('atividade', 'disciplina')
            ->get();

        return View::render('agendamentos/index', [
            'agendamentos' => $agendamentos,
        ]);
    }

    public function ver(Agendamento $agendamento)
    {
        return View::render('agendamentos/ver', [
            'agendamento' => $agendamento,
        ]);
    }
}
