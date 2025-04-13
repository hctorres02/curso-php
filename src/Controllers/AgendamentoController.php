<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Agendamento;
use App\Models\Atividade;
use App\Models\Disciplina;
use App\Models\Usuario;
use App\Notifications\AgendamentoAtualizado;
use App\Notifications\AgendamentoCadastrado;
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

    public function salvar(Request $request): RedirectResponse
    {
        if (! $request->validate(Agendamento::rules())) {
            return redirectRoute('cadastrar_agendamento');
        }

        $agendamento = Agendamento::create($request->validated);

        notifyMany(Usuario::pluck('id')->toArray(), AgendamentoCadastrado::class, [
            'agendamento' => $agendamento->id,
        ]);

        return redirectRoute('agendamentos');
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
            return redirectRoute('editar_agendamento', $agendamento->id);
        }

        $agendamento->update($request->validated);


        notifyMany(Usuario::pluck('id')->toArray(), AgendamentoAtualizado::class, [
            'agendamento' => $agendamento->id,
        ]);

        return redirectRoute('agendamentos');
    }

    public function excluir(Agendamento $agendamento): RedirectResponse
    {
        $agendamento->delete();

        return redirectRoute('agendamentos');
    }
}
