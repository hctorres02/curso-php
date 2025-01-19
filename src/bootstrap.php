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
