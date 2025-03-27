<?php

use App\Http\Request;
use App\Http\Router;
use App\Middlewares\ConfigurePaginator;
use App\Middlewares\ConfigureView;
use App\Middlewares\EnableHttpMethodParameterOverride;
use App\Middlewares\StartSession;
use App\Middlewares\ValidateCsrfToken;

require __DIR__.'/../src/bootstrap.php';
require __DIR__.'/../src/routes.php';

// captura requisição
$request = Request::boot();

// despacha rota e captura resposta
$response = Router::dispatch($request, [
    StartSession::class,
    ConfigureView::class,
    ConfigurePaginator::class,
    EnableHttpMethodParameterOverride::class,
    ValidateCsrfToken::class,
]);

// envia resposta
$response->send();
