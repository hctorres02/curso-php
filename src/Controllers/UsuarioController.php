<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Usuario;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController
{
    public function index(): RedirectResponse
    {
        return redirect('/usuarios/editar');
    }

    public function cadastrar(): Response
    {
        return response('usuarios/cadastrar');
    }

    public function salvar(Request $request): RedirectResponse
    {
        if (! $request->validate(Usuario::rules())) {
            return redirect('/usuarios/cadastrar');
        }

        $usuario = Usuario::create($request->validated);

        session()->set('usuario', $usuario);

        return redirect($request->attemptedUri('/agendamentos'));
    }

    public function editar(): Response
    {
        return response('usuarios/editar');
    }

    public function atualizar(Request $request): RedirectResponse
    {
        if (! $request->validate(Usuario::rules(), ['nome', 'email'])) {
            return redirect('/usuarios/editar');
        }

        $usuario = session()->get('usuario');

        $usuario->update($request->validated);
        $usuario->refresh();

        session()->set('usuario', $usuario);
        session()->migrate(true);

        return redirect('/usuarios/editar');
    }
}
