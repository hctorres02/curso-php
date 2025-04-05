<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Atividade;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AtividadeController
{
    public function index(Request $request): Response
    {
        $data = Atividade::toSearch([
            'nome' => $request->get('nome'),
        ]);

        return response('atividades/index', $data);
    }

    public function cadastrar(): Response
    {
        return response('atividades/cadastrar');
    }

    public function salvar(Request $request): RedirectResponse
    {
        if (! $request->validate(Atividade::rules())) {
            return redirect(route('cadastrar_atividade'));
        }

        $atividade = Atividade::create($request->validated);

        return redirect(route('atividades'));
    }

    public function editar(Atividade $atividade): Response
    {
        return response('atividades/editar', compact('atividade'));
    }

    public function atualizar(Request $request, Atividade $atividade): RedirectResponse
    {
        if (! $request->validate(Atividade::rules())) {
            return redirect(route('editar_atividade', $atividade->id));
        }

        $atividade->update($request->validated);

        return redirect(route('atividades'));
    }

    public function excluir(Atividade $atividade): RedirectResponse
    {
        $atividade->agendamentos()->delete();
        $atividade->delete();

        return redirect(route('atividades'));
    }
}
