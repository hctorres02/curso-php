<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Disciplina;
use App\Models\Periodo;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DisciplinaController
{
    public function index(Request $request): Response
    {
        $data = Disciplina::toSearch([
            'nome' => $request->get('nome'),
            'periodo_id' => $request->get('periodo_id'),
        ]);

        return response('disciplinas/index', $data);
    }

    public function cadastrar(): Response
    {
        $periodos = Periodo::query()
            ->select('id')
            ->selectRaw("(ano||'.'||semestre) AS nome")
            ->pluck('nome', 'id')
            ->sortDesc();

        return response('disciplinas/cadastrar', compact('periodos'));
    }

    public function salvar(Request $request): RedirectResponse
    {
        if (! $request->validate(Disciplina::rules())) {
            return redirect('/disciplinas/cadastrar');
        }

        $disciplina = Disciplina::create($request->validated);

        return redirect('/disciplinas');
    }

    public function editar(Disciplina $disciplina): Response
    {
        $periodos = Periodo::query()
            ->select('id')
            ->selectRaw("(ano||'.'||semestre) AS nome")
            ->pluck('nome', 'id')
            ->sortDesc();

        return response('disciplinas/editar', compact('periodos', 'disciplina'));
    }

    public function atualizar(Request $request, Disciplina $disciplina): RedirectResponse
    {
        if (! $request->validate(Disciplina::rules())) {
            return redirect("/disciplinas/{$disciplina->id}/editar");
        }

        $disciplina->update([
            'periodo_id' => $request->get('periodo_id'),
            'nome' => $request->get('nome'),
            'cor' => $request->get('cor'),
        ]);

        return redirect('/disciplinas');
    }

    public function excluir(Disciplina $disciplina): RedirectResponse
    {
        $disciplina->agendamentos()->delete();
        $disciplina->delete();

        return redirect('/disciplinas');
    }
}
