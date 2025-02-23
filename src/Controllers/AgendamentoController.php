<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Agendamento;
use App\Models\Atividade;
use App\Models\Disciplina;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AgendamentoController
{
    public function index(Request $request): Response
    {
        $data = Agendamento::toSearch([
            'periodo_id' => $request->get('periodo_id'),
            'atividade_id' => $request->get('atividade_id'),
            'disciplina_id' => $request->get('disciplina_id'),
        ]);

        return response('agendamentos/index', $data);
    }

    public function cadastrar(): Response
    {
        $atividades = Atividade::toOptGroup();
        $disciplinas = Disciplina::toOptGroup();

        return response('agendamentos/cadastrar', compact('atividades', 'disciplinas'));
    }

    public function salvar(Request $request): Response
    {
        if (! $request->validate(Agendamento::rules())) {
            return redirect('/agendamentos/cadastrar');
        }

        $agendamento = Agendamento::create($request->validated);

        return redirect('/agendamentos');
    }

    public function ver(Agendamento $agendamento): Response
    {
        return response('agendamentos/ver', compact('agendamento'));
    }

    public function editar(Agendamento $agendamento): Response
    {
        $atividades = Atividade::toOptGroup();
        $disciplinas = Disciplina::toOptGroup();

        return response('agendamentos/editar', compact('atividades', 'disciplinas', 'agendamento'));
    }

    public function atualizar(Request $request, Agendamento $agendamento): RedirectResponse
    {
        if (! $request->validate(Agendamento::rules())) {
            return redirect("/agendamentos/{$agendamento->id}/editar");
        }

        $agendamento->update($request->validated);

        return redirect('/agendamentos');
    }

    public function excluir(Agendamento $agendamento): RedirectResponse
    {
        $agendamento->delete();

        return redirect('/agendamentos');
    }
}
