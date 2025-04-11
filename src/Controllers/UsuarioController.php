<?php

namespace App\Controllers;

use App\Enums\Permission;
use App\Enums\Role;
use App\Http\Request;
use App\Models\Usuario;
use App\Notifications\UsuarioCadastrado;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController
{
    public function index(Request $request): Response
    {
        $data = Usuario::toSearch([
            'q' => $request->get('q'),
            'role' => $request->get('role'),
        ]);

        return response('/usuarios/index', $data);
    }

    public function cadastrar(): Response
    {
        $roles = Role::toArray();
        $permissions = Permission::toArray();

        return response('usuarios/cadastrar', compact('roles', 'permissions'));
    }

    public function salvar(Request $request): RedirectResponse
    {
        if (! $request->validate(Usuario::rules(), [
            'nome',
            'email',
            'senha',
            'role' => Permission::ATRIBUIR_ROLE,
            'permissions' => Permission::ATRIBUIR_PERMISSOES,
        ])) {
            return redirect(route('cadastrar_usuario'));
        }

        $usuario = Usuario::create($request->validated);

        notify($usuario, UsuarioCadastrado::class);

        return redirect(route('editar_usuario', $usuario->id));
    }

    public function editar(Usuario $usuario): Response
    {
        $roles = Role::toArray();
        $permissions = Permission::toArray();

        return response('usuarios/editar', compact('usuario', 'roles', 'permissions'));
    }

    public function atualizar(Request $request, Usuario $usuario): RedirectResponse
    {
        if ($request->validate(Usuario::rules(), [
            'nome',
            'email',
            'role' => Permission::ATRIBUIR_ROLE,
            'permissions' => Permission::ATRIBUIR_PERMISSOES,
        ])) {
            $usuario->update($request->validated);
        }


        return redirect('editar_usuario', $usuario->id);
    }
}
