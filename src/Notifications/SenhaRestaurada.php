<?php

namespace App\Notifications;

use App\Models\Usuario;
use App\Traits\Notifiable;

class SenhaRestaurada
{
    use Notifiable;

    public function __invoke(Usuario $recipient): void
    {
        $data = [
            'recipient' => $recipient,
            'subject' => 'Senha restaurada',
        ];

        $this->viaEmail('notifications/email/senha_restaurada.twig', $data);
    }
}
