<?php

namespace App\Controllers;

use App\Models\Agendamento;

class AgendamentoController
{
    public function index()
    {
        return 'Agendamentos';
    }

    public function ver(Agendamento $agendamento)
    {
        return "Visualizando agendamento #{$agendamento->id}: {$agendamento->data}";
    }
}
