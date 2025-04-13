<?php

namespace App\Notifications;

use App\Models\Usuario;
use App\Traits\Notifiable;
use Spatie\Menu\Link;

class RecuperarSenha
{
    use Notifiable;

    public function __invoke(Usuario $recipient): void
    {
        $data = [
            'recipient' => $recipient,
            'subject' => 'Redefinir senha',
            'cta' => Link::to(signedRoute('redefinir_senha', $recipient->only('email')), 'Redefinir senha'),
        ];

        $this->viaEmail('notifications/email/recuperar_senha.twig', $data);
    }
}
