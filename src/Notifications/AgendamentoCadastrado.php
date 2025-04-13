<?php

namespace App\Notifications;

use App\Models\Agendamento;
use App\Models\Usuario;
use App\Traits\Notifiable;
use Spatie\Menu\Link;

class AgendamentoCadastrado
{
    use Notifiable;

    public function __invoke(Usuario $recipient, Agendamento $agendamento): void
    {
        $atividade = ucfirst($agendamento->atividade->nome);
        $disciplina = ucwords($agendamento->disciplina->nome);
        $dm = $agendamento->data->format('d/m');
        $id = $agendamento->id;

        $data = [
            'recipient' => $recipient,
            'subject' => "Agendamento #{$id}: {$atividade} de {$disciplina} para {$dm}",
            'cta' => Link::to(route('ver_agendamento', $agendamento->id), 'Ver agendamento'),
            'atividade' => $atividade,
            'disciplina' => $disciplina,
            'dm' => $dm,
        ];

        $this->viaEmail('notifications/email/agendamento', $data);
    }
}
