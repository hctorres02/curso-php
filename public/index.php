<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../src/bootstrap.php';

// captura requisição
$request = Request::createFromGlobals();;

// despacha rota e captura resposta
$response = App\Http\Router::dispatch($request);

// envia resposta
$response->send();
