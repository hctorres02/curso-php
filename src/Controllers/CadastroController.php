<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Usuario;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class CadastroController
{
    public function cadastrar(): Response
    {
        return response('cadastro/cadastrar');
    }

    public function salvar(Request $request): RedirectResponse
    {
        if (! $request->validate(Usuario::rules(), ['nome', 'email', 'senha'])) {
            return redirect('/cadastro');
        }

        Usuario::create($request->validated);

        return redirect('/login');
    }

    public function editar(): Response
    {
        $usuario = session()->get('usuario');

        return response('cadastro/editar', compact('usuario'));
    }

    public function atualizar(Request $request): RedirectResponse
    {
        if (! $request->validate(Usuario::rules(), ['nome', 'email'])) {
            return redirect('/cadastro/editar');
        }

        session()->get('usuario')->update($request->validated);
        session()->migrate(true);

        return redirect('/cadastro/editar');
    }
}
