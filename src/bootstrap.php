<?php

use App\Http\Http;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Events\Dispatcher;
use Monolog\Level;
use Symfony\Component\HttpFoundation\Response;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

// raíz do projeto
define('PROJECT_ROOT', realpath(__DIR__.'/..'));

// raíz pública
define('WEB_ROOT', PROJECT_ROOT.'/public');

// auto carregamento
require PROJECT_ROOT.'/vendor/autoload.php';

// variáveis de ambiente
Dotenv::createImmutable(PROJECT_ROOT)->load();

// timezone
date_default_timezone_set(env('APP_TIMEZONE'));

// coletor de erros
tap(new Run, function (Run $runner) {
    $handler = env('APP_DEBUG') ? new PrettyPageHandler : function (Throwable $exception) {
        // define a resposta apropriada
        $response = match (true) {
            $exception instanceof ModelNotFoundException => responseError(Response::HTTP_NOT_FOUND, $exception, Level::Info),
            default => responseError(Response::HTTP_INTERNAL_SERVER_ERROR, $exception)
        };

        // enviar resposta
        $response->send();
    };

    $runner->prependHandler($handler);
    $runner->register();
});

// banco de dados
tap(new Manager, function (Manager $dbManager) {
    $dbManager->addConnection(require PROJECT_ROOT.'/config/database.php');
    $dbManager->setEventDispatcher(new Dispatcher);
    $dbManager->setAsGlobal();
    $dbManager->bootEloquent();
});

// cliente HTTP
Http::boot(require PROJECT_ROOT.'/config/http.php');
