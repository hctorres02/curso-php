<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\View;
use App\Models\Disciplina;
use App\Models\Periodo;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DisciplinaController
{
    public function index(Request $request)
    {
        $data = Disciplina::toSearch([
            'nome' => $request->get('nome'),
            'periodo_id' => $request->get('periodo_id'),
        ]);

        return View::render('disciplinas/index', $data);
    }

    public function cadastrar()
    {
        $periodos = Periodo::query()
            ->select('id')
            ->selectRaw("(ano||'.'||semestre) AS nome")
            ->pluck('nome', 'id')
            ->sortDesc();

        return view::render('disciplinas/cadastrar', compact('periodos'));
    }

    public function salvar(Request $request)
    {
        $disciplina = Disciplina::create([
            'periodo_id' => $request->get('periodo_id'),
            'nome' => $request->get('nome'),
            'cor' => $request->get('cor'),
        ]);

        return new RedirectResponse('/disciplinas');
    }

    public function editar(Disciplina $disciplina)
    {
        $periodos = Periodo::query()
            ->select('id')
            ->selectRaw("(ano||'.'||semestre) AS nome")
            ->pluck('nome', 'id')
            ->sortDesc();

        return view::render('disciplinas/editar', compact('periodos', 'disciplina'));
    }

    public function atualizar(Request $request, Disciplina $disciplina)
    {
        $disciplina->update([
            'periodo_id' => $request->get('periodo_id'),
            'nome' => $request->get('nome'),
            'cor' => $request->get('cor'),
        ]);

        return new RedirectResponse('/disciplinas');
    }

    public function excluir(Disciplina $disciplina)
    {
        $disciplina->agendamentos()->delete();
        $disciplina->delete();

        return new RedirectResponse('/disciplinas');
    }
}
