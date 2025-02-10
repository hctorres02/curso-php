<?php

namespace App\Controllers;

use App\Http\View;
use App\Models\Agendamento;
use Symfony\Component\HttpFoundation\Request;

class AgendamentoController
{
    public function index(Request $request)
    {
        $data = Agendamento::toSearch([
            'periodo_id' => $request->get('periodo_id'),
            'atividade_id' => $request->get('atividade_id'),
            'disciplina_id' => $request->get('disciplina_id'),
        ]);

        return View::render('agendamentos/index', $data);
    }

    public function ver(Agendamento $agendamento)
    {
        return View::render('agendamentos/ver', [
            'agendamento' => $agendamento,
        ]);
    }
}
