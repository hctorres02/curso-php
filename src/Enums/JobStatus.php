<?php

namespace App\Enums;

use App\Traits\EnumSupport;

enum JobStatus: string
{
    use EnumSupport;

    case DONE = 'done';
    case FAILED = 'failed';
    case PENDING = 'pending';

    public function getMessage(): string
    {
        return match ($this) {
            self::DONE => 'Executado',
            self::FAILED => 'Falhou',
            self::PENDING => 'Execução pendente',
        };
    }
}
