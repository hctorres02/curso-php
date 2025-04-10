<?php

namespace App\Notifications;

use App\Models\Usuario;
use App\Traits\Notifiable;
use Spatie\Menu\Link;

class UsuarioCadastrado
{
    use Notifiable;

    public function __invoke(Usuario $recipient): void
    {
        $data = [
            'recipient' => $recipient,
            'subject' => "Seja bem vindo, {$recipient->nome}",
            'cta' => Link::to(route('login'), 'Fazer login'),
        ];

        $this->viaEmail('notifications/email/usuario_cadastrado', $data);
    }
}
