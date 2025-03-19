<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Usuario;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController
{
    public function index(Request $request): Response
    {
        $data = Usuario::toSearch([
            'q' => $request->get('q'),
        ]);

        return response('/usuarios/index', $data);
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

        return redirect("/usuarios/{$usuario->id}/editar");
    }

    public function editar(Usuario $usuario): Response
    {
        return response('usuarios/editar', compact('usuario'));
    }

    public function atualizar(Request $request, Usuario $usuario): RedirectResponse
    {
        if (! $request->validate(Usuario::rules(), ['nome', 'email'])) {
            return redirect("/usuarios/{$usuario->id}/editar");
        }

        $usuario->update($request->validated);

        return redirect("/usuarios/{$usuario->id}/editar");
    }
}
