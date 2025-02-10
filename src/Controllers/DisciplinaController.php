<?php

namespace App\Controllers;

use App\Http\View;
use App\Models\Disciplina;
use Symfony\Component\HttpFoundation\Request;

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
}
