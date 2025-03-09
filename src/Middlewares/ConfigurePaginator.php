<?php

namespace App\Middlewares;

use App\Http\Request;
use Illuminate\Pagination\Paginator;

class ConfigurePaginator
{
    public function __invoke(Request $request): bool
    {
        Paginator::currentPathResolver(fn () => $request->getPathInfo());
        Paginator::currentPageResolver(fn ($pageName) => $request->get($pageName));

        return true;
    }
}
