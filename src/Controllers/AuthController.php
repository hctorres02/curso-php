<?php

namespace App\Controllers;

use App\Http\Auth;
use App\Http\Request;
use App\Models\Usuario;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function index(): Response
    {
        return response('auth/login');
    }

    public function login(Request $request): RedirectResponse
    {
        if (
            $request->validate(Usuario::rules(), ['email', 'senha']) &&
            Auth::attempt($request->validated)
        ) {
            return redirect($request->attemptedUri('/agendamentos'));
        }

        return redirect('/login');
    }

    public function logout(): RedirectResponse
    {
        session()->invalidate();

        return redirect('/login');
    }
}
