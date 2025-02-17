<?php

namespace App\Controllers;

use App\Http\View;
use App\Models\Agendamento;
use App\Models\Atividade;
use App\Models\Disciplina;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    public function cadastrar()
    {
        $atividades = Atividade::toOptGroup();
        $disciplinas = Disciplina::toOptGroup();

        return View::render('agendamentos/cadastrar', compact('atividades', 'disciplinas'));
    }

    public function salvar(Request $request)
    {
        $agendamento = Agendamento::create([
            'atividade_id' => $request->get('atividade_id'),
            'disciplina_id' => $request->get('disciplina_id'),
            'conteudo' => $request->get('conteudo'),
            'data' => $request->get('data'),
        ]);

        return new RedirectResponse('/agendamentos');
    }

    public function ver(Agendamento $agendamento)
    {
        return View::render('agendamentos/ver', compact('agendamento'));
    }

    public function editar(Agendamento $agendamento)
    {
        $atividades = Atividade::toOptGroup();
        $disciplinas = Disciplina::toOptGroup();

        return View::render('agendamentos/editar', compact('atividades', 'disciplinas', 'agendamento'));
    }

    public function atualizar(Request $request, Agendamento $agendamento)
    {
        $agendamento->update([
            'atividade_id' => $request->get('atividade_id'),
            'disciplina_id' => $request->get('disciplina_id'),
            'conteudo' => $request->get('conteudo'),
            'data' => $request->get('data'),
        ]);

        return new RedirectResponse('/agendamentos');
    }

    public function excluir(Agendamento $agendamento)
    {
        $agendamento->delete();

        return new RedirectResponse('/agendamentos');
    }
}
