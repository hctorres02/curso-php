<?php

namespace App\Enums;

use App\Traits\EnumSupport;

enum Role: string
{
    use EnumSupport;

    case ADMINISTRADOR = 'administrador';
    case CONTRIBUIDOR = 'contribuidor';
    case VISITANTE = 'visitante';
}
