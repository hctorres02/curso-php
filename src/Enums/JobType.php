<?php

namespace App\Enums;

use App\Traits\EnumSupport;

enum JobType: string
{
    use EnumSupport;

    case NOTIFICATION = 'notification';
}
