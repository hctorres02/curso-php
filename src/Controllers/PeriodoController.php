<?php

namespace App\Controllers;

use App\Http\View;
use App\Models\Periodo;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PeriodoController
{
    public function index(Request $request)
    {
        $data = Periodo::toSearch([
            'q' => $request->get('q'),
        ]);

        return View::render('periodos/index', $data);
    }

    public function cadastrar()
    {
        return View::render('periodos/cadastrar');
    }

    public function salvar(Request $request)
    {
        $periodo = Periodo::create([
            'ano' => $request->get('ano'),
            'semestre' => $request->get('semestre'),
        ]);

        return new RedirectResponse('/periodos');
    }

    public function editar(Periodo $periodo)
    {
        return View::render('periodos/editar', compact('periodo'));
    }

    public function atualizar(Request $request, Periodo $periodo)
    {
        $periodo->update([
            'ano' => $request->get('ano'),
            'semestre' => $request->get('semestre'),
        ]);

        return new RedirectResponse('/periodos');
    }
}
