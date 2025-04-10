<?php

namespace App\Notifications;

use App\Models\Usuario;
use App\Traits\Notifiable;
use Spatie\Menu\Link;

class SenhaRedefinida
{
    use Notifiable;

    public function __invoke(Usuario $recipient): void
    {
        $data = [
            'recipient' => $recipient,
            'subject' => 'Senha redefinida',
            'cta' => Link::to(signedRoute('restaurar_senha', $recipient->only('email')), 'Restaurar senha'),
        ];

        $this->viaEmail('notifications/email/senha_redefinida', $data);
    }
}
