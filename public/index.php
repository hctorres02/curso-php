<?php

use App\Http\Request;
use App\Http\Router;
use App\Http\View;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

require __DIR__.'/../src/bootstrap.php';

// captura requisição
$request = Request::boot();

// habilita reescrita de método (PUT, PATCH, DELETE)
$request->enableHttpMethodParameterOverride();

// define sessão
$request->setSession(new Session(
    new NativeSessionStorage(handler: new NativeFileSessionHandler)
));

// adiciona variáveis globais ao contexto da view
View::addGlobals([
    'APP_LOCALE' => str_replace('_', '-', env('APP_LOCALE')),
    'CSRF_TOKEN' => $request->generateCsrfToken(),
    'CURRENT_URI' => $request->getPathInfo(),
    'ERRORS' => $request->getErrors(),
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
Illuminate\Pagination\Paginator::currentPageResolver(
    fn ($pageName) => $request->get($pageName)
);

// paginação
Illuminate\Pagination\Paginator::currentPathResolver(
    fn () => $request->getPathInfo()
);

// despacha rota e captura resposta
$response = Router::dispatch($request);

// envia resposta
$response->send();
