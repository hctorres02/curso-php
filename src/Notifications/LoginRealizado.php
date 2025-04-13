<?php

namespace App\Notifications;

use App\Models\Usuario;
use App\Traits\Notifiable;
use Spatie\Menu\Link;

class LoginRealizado
{
    use Notifiable;

    public function __invoke(Usuario $recipient): void
    {
        $data = [
            'recipient' => $recipient,
            'subject' => 'Login realizado',
            'cta' => Link::to(signedRoute('redefinir_senha', $recipient->only('email')), 'Redefinir senha'),
        ];

        if ($recipient->email == env('ADMIN_EMAIL')) {
            unset($data['cta']);
        }

        $this->viaEmail('notifications/email/login_realizado.twig', $data);
    }
}
