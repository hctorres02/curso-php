<?php

namespace App\Controllers;

use App\Http\View;
use App\Models\Atividade;
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
}
