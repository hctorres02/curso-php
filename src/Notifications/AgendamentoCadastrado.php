<?php

namespace App\Notifications;

use App\Models\Agendamento;
use App\Models\Anexo;
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
        $anexos = $agendamento->anexos()->get()->map(fn (Anexo $anexo) => [
            'name' => $anexo->nome_original,
            'path' => Anexo::uploadsDir($anexo->caminho),
            'contentType' => $anexo->tipo,
        ])->toArray();

        $data = [
            'recipient' => $recipient,
            'subject' => "Agendamento #{$id}: {$atividade} de {$disciplina} para {$dm}",
            'cta' => Link::to(route('ver_agendamento', $agendamento->id), 'Ver agendamento'),
            'atividade' => $atividade,
            'disciplina' => $disciplina,
            'dm' => $dm,
            'attachments' => $anexos,
        ];

        $this->viaEmail('notifications/email/agendamento.twig', $data);
    }
}
