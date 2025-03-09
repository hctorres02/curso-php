<?php

namespace App\Middlewares;

use App\Http\Request;

class EnableHttpMethodParameterOverride
{
    public function __invoke(Request $request): bool
    {
        $request->enableHttpMethodParameterOverride();

        return true;
    }
}
