<?php

use App\Http\Request;
use App\Http\Router;
use App\Http\View;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

require __DIR__.'/../src/bootstrap.php';

// captura requisição
$request = Request::boot();

// habilita reescrita de método (PUT, PATCH, DELETE)
$request->enableHttpMethodParameterOverride();

// define sessão
$request->setSession(new Session(
    new NativeSessionStorage(handler: new PdoSessionHandler(DB::connection()->getPdo()))
));

// adiciona variáveis globais ao contexto da view
View::addGlobals([
    'APP_LOCALE' => str_replace('_', '-', env('APP_LOCALE')),
    'CSRF_TOKEN' => fn (Request $request) => $request->generateCsrfToken(),
    'CURRENT_URI' => fn (Request $request) => $request->getPathInfo(),
    'ERRORS' => fn (Request $request) => $request->getErrors(),
    'MAIN_MENU' => [
        'Agendamentos' => '/agendamentos',
        'Períodos' => '/periodos',
        'Disciplinas' => '/disciplinas',
        'Atividades' => '/atividades',
    ],
]);

// adiciona helper ATTR
View::addFunction('attr', attr(...));

// adiciona helper URL
View::addFunction('url', url(...));

// paginação
Paginator::currentPageResolver(fn ($pageName) => $request->get($pageName));

// paginação
Paginator::currentPathResolver(fn () => $request->getPathInfo());

// despacha rota e captura resposta
$response = Router::dispatch($request);

// envia resposta
$response->send();
