<?php

return [
    'channel' => 'local',
    'date_format' => 'd/m/Y H:i:s',
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'filename' => PROJECT_ROOT.'/logs/app.log',
];
