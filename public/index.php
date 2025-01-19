<?php

use Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require_once '../vendor/autoload.php';

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

$whoops = new Run;
$whoops->prependHandler(new PrettyPageHandler);
$whoops->register();

echo hello_world();
