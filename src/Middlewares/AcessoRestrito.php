<?php

namespace App\Middlewares;

use App\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AcessoRestrito
{
    public function __invoke(Request $request) : bool|RedirectResponse
    {
        if (session()->get('usuario')) {
            return true;
        }

        session()->set('attempted_uri', $request->getRequestUri());

        return redirect('/login');
    }
}
