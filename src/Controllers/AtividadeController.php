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

        return response('atividades/index.twig', $data);
    }

    public function cadastrar(): Response
    {
        return response('atividades/cadastrar.twig');
    }

    public function salvar(Request $request): RedirectResponse
    {
        if (! $request->validate(Atividade::rules())) {
            return redirectRoute('cadastrar_atividade');
        }

        $atividade = Atividade::create($request->validated);

        return redirectRoute('atividades');
    }

    public function editar(Atividade $atividade): Response
    {
        return response('atividades/editar.twig', compact('atividade'));
    }

    public function atualizar(Request $request, Atividade $atividade): RedirectResponse
    {
        if (! $request->validate(Atividade::rules())) {
            return redirectRoute('editar_atividade', $atividade->id);
        }

        $atividade->update($request->validated);

        return redirectRoute('atividades');
    }

    public function excluir(Atividade $atividade): RedirectResponse
    {
        $atividade->agendamentos()->delete();
        $atividade->delete();

        return redirectRoute('atividades');
    }
}
