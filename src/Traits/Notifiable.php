<?php

namespace App\Traits;

use App\Http\View;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

trait Notifiable
{
    private function viaEmail(string $view, array $data): void
    {
        $transport = Transport::fromDsn(env('MAILER_DSN'));
        $mailer = new Mailer($transport);
        $message = new Email;
        $body = View::render($view, $data);
        $attachments = $data['attachments'] ?? [];

        $message->from(env('MAILER_FROM'));
        $message->to($data['recipient']->email);
        $message->subject($data['subject']);
        $message->html($body);

        foreach ($attachments as $attachment) {
            $message->attachFromPath(
                $attachment['path'],
                $attachment['name'],
                $attachment['contentType']
            );
        }

        $mailer->send($message);
    }
}
