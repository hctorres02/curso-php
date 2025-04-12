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
            return redirectRoute('cadastrar_periodo');
        }

        $periodo = Periodo::create($request->validated);

        return redirectRoute('periodos');
    }

    public function editar(Periodo $periodo): Response
    {
        return response('periodos/editar', compact('periodo'));
    }

    public function atualizar(Request $request, Periodo $periodo): RedirectResponse
    {
        if (! $request->validate(Periodo::rules())) {
            return redirectRoute('editar_periodo', $periodo->id);
        }

        $periodo->update($request->validated);

        return redirectRoute('periodos');
    }

    public function excluir(Periodo $periodo): RedirectResponse
    {
        $periodo->agendamentos()->delete();
        $periodo->disciplinas()->delete();
        $periodo->delete();

        return redirectRoute('periodos');
    }
}
