<?php

namespace App\Notifications;

use App\Models\Agendamento;
use App\Models\Usuario;
use App\Traits\Notifiable;

class AgendaDiaria
{
    use Notifiable;

    public function __invoke(Usuario $recipient, array $agendamentos): void
    {
        $hoje = today()->format('d/m');

        $data = [
            'recipient' => $recipient,
            'subject' => "Agenda diária {$hoje}",
            'agendamentos' => Agendamento::whereIn('id', $agendamentos)->get(),
            'hoje' => $hoje,
        ];

        $this->viaEmail('notifications/email/agenda_diaria.twig', $data);
    }
}
