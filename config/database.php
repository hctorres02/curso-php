<?php

return [
    'driver' => 'sqlite',
    'database' => PROJECT_ROOT.DIRECTORY_SEPARATOR.env('DB_DATABASE', 'database/app.sqlite'),
    'foreign_key_constraints' => true,
];
