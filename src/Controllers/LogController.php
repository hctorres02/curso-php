<?php

namespace App\Controllers;

use App\Http\Request;
use Monolog\Level;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class LogController
{
    public function index(Request $request): Response
    {
        $levels = array_combine(Level::NAMES, Level::NAMES);
        $level_name = $levels[$request->get('level_name')] ?? null;

        $limits = [5 => 5, 10 => 10, 20 => 20];
        $limit = $limits[$request->get('limit')] ?? 5;

        $log = logReader($limit, $level_name);

        flash()->set('filter:log', compact('level_name', 'limit'));

        return response('logs', compact('levels', 'level_name', 'limits', 'limit', 'log'));
    }

    public function excluir(?string $key): RedirectResponse
    {
        if ($key && logRemoveLine($key)) {
            session()->remove('csrf_token');
            session()->migrate();
        }

        return redirectRoute('logs', flash()->get('filter:log'));
    }
}
