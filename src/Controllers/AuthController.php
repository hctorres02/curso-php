<?php

namespace App\Controllers;

use App\Http\Auth;
use App\Http\Request;
use App\Models\Usuario;
use App\Notifications\LoginRealizado;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function index(): Response
    {
        return response('auth/login.twig');
    }

    public function login(Request $request): RedirectResponse
    {
        if (
            $request->validate(Usuario::rules(), ['email', 'senha']) &&
            $usuario = Auth::attempt($request->validated)
        ) {
            notify($usuario, LoginRealizado::class, [
                'user_agent' => $request->headers->get('user-agent', 'Não identificado'),
                'ip' => $request->getClientIp(),
            ]);

            return redirect($request->attemptedUri(route('agendamentos')));
        }

        return redirectRoute('login');
    }

    public function logout(): RedirectResponse
    {
        session()->invalidate();

        return redirectRoute('login');
    }
}
