<?php

// raíz do projeto
define('PROJECT_ROOT', realpath(__DIR__ . '/..'));

// raíz pública
define('WEB_ROOT', PROJECT_ROOT . '/public');

// auto carregamento
require PROJECT_ROOT . '/vendor/autoload.php';

// variáveis de ambiente
Dotenv\Dotenv::createImmutable(PROJECT_ROOT)->load();

// coletor de erros
(new Whoops\Run)->prependHandler(new Whoops\Handler\PrettyPageHandler)->register();

// banco de dados
$dbManager = new \Illuminate\Database\Capsule\Manager;
$dbManager->addConnection(require PROJECT_ROOT . '/config/database.php');
$dbManager->setAsGlobal();
$dbManager->bootEloquent();
