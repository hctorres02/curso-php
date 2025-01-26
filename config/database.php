<?php

return [
    'driver' => 'sqlite',
    'database' => env('DB_DATABASE', PROJECT_ROOT . '/database/app.sqlite'),
    'foreign_key_constraints' => true,
];
