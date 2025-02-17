<?php

namespace App\Controllers;

use App\Http\View;
use App\Models\Atividade;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

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
        $atividade = Atividade::create([
            'nome' => $request->get('nome'),
            'cor' => $request->get('cor'),
        ]);

        return new RedirectResponse('/atividades');
    }

    public function editar(Atividade $atividade)
    {
        return View::render('atividades/editar', compact('atividade'));
    }

    public function atualizar(Request $request, Atividade $atividade)
    {
        $atividade->update([
            'nome' => $request->get('nome'),
            'cor' => $request->get('cor'),
        ]);

        return new RedirectResponse('/atividades');
    }

    public function excluir(Atividade $atividade)
    {
        $atividade->agendamentos()->delete();
        $atividade->delete();

        return new RedirectResponse('/atividades');
    }
}
