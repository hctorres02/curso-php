<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\View;
use App\Models\Atividade;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AtividadeController
{
    public function index(Request $request)
    {
        $data = Atividade::toSearch([
            'nome' => $request->get('nome'),
        ]);

        return View::render('atividades/index', $data);
    }

    public function cadastrar()
    {
        return View::render('atividades/cadastrar');
    }

    public function salvar(Request $request)
    {
        if (! $request->validate(Atividade::rules())) {
            return new RedirectResponse('/atividades/cadastrar');
        }

        $atividade = Atividade::create($request->validated);

        return new RedirectResponse('/atividades');
    }

    public function editar(Atividade $atividade)
    {
        return View::render('atividades/editar', compact('atividade'));
    }

    public function atualizar(Request $request, Atividade $atividade)
    {
        if (! $request->validate(Atividade::rules())) {
            return new RedirectResponse("/atividades/{$atividade->id}/editar");
        }

        $atividade->update($request->validated);

        return new RedirectResponse('/atividades');
    }

    public function excluir(Atividade $atividade)
    {
        $atividade->agendamentos()->delete();
        $atividade->delete();

        return new RedirectResponse('/atividades');
    }
}
