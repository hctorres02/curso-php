<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Usuario;
use App\Notifications\UsuarioCadastrado;
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
            return redirect(route('cadastro'));
        }

        $usuario = Usuario::create($request->validated);

        notify($usuario, UsuarioCadastrado::class);

        return redirect(route('login'));
    }

    public function editar(): Response
    {
        $usuario = session()->get('usuario');

        return response('cadastro/editar', compact('usuario'));
    }

    public function atualizar(Request $request): RedirectResponse
    {
        if (! $request->validate(Usuario::rules(), ['nome', 'email'])) {
            return redirect(route('editar_cadastro'));
        }

        session()->get('usuario')->update($request->validated);
        session()->migrate(true);

        return redirect(route('editar_cadastro'));
    }
}
