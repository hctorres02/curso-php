<?php

namespace App\Controllers;

use App\Http\View;
use App\Models\Periodo;
use Symfony\Component\HttpFoundation\Request;

class PeriodoController
{
    public function index(Request $request)
    {
        $data = Periodo::toSearch([
            'q' => $request->get('q'),
        ]);

        return View::render('periodos/index', $data);
    }
}
