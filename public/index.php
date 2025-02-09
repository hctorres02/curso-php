<?php

use App\Http\Router;
use App\Http\View;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../src/bootstrap.php';

// captura requisição
$request = Request::createFromGlobals();;

// adiciona variáveis globais ao contexto da view
View::addGlobals([
    'APP_LOCALE' => str_replace('_', '-', env('APP_LOCALE')),
    'CURRENT_URI' => $request->getPathInfo(),
]);

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
