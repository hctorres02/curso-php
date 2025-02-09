<?php

namespace App\Controllers;

use App\Http\View;
use App\Models\Agendamento;

class AgendamentoController
{
    public function index()
    {
        $data = Agendamento::query()
            ->previstos()
            ->oldest('data')
            ->with('atividade', 'disciplina')
            ->paginate(5)
            ->toArray();

        return View::render('agendamentos/index', $data);
    }

    public function ver(Agendamento $agendamento)
    {
        return View::render('agendamentos/ver', [
            'agendamento' => $agendamento,
        ]);
    }
}
