<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Periodo;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class PeriodoController
{
    public function index(Request $request): Response
    {
        $data = Periodo::toSearch([
            'q' => $request->get('q'),
        ]);

        return response('periodos/index', $data);
    }

    public function cadastrar(): Response
    {
        return response('periodos/cadastrar');
    }

    public function salvar(Request $request): RedirectResponse
    {
        if (! $request->validate(Periodo::rules())) {
            return redirect('/periodos/cadastrar');
        }

        $periodo = Periodo::create($request->validated);

        return redirect('/periodos');
    }

    public function editar(Periodo $periodo): Response
    {
        return response('periodos/editar', compact('periodo'));
    }

    public function atualizar(Request $request, Periodo $periodo): RedirectResponse
    {
        if (! $request->validate(Periodo::rules())) {
            return redirect("/periodos/{$periodo->id}/editar");
        }

        $periodo->update($request->validated);

        return redirect('/periodos');
    }

    public function excluir(Periodo $periodo): RedirectResponse
    {
        $periodo->agendamentos()->delete();
        $periodo->disciplinas()->delete();
        $periodo->delete();

        return redirect('/periodos');
    }
}
