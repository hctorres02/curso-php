<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Usuario;
use App\Notifications\RecuperarSenha;
use App\Notifications\SenhaRedefinida;
use App\Notifications\SenhaRestaurada;
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

    public function recuperarSenha(): Response
    {
        return response('cadastro/recuperar_senha');
    }

    public function submitRecuperarSenha(Request $request): RedirectResponse
    {
        if (
            ! $request->validate(Usuario::rules(), ['email']) ||
            ! $usuario = Usuario::firstWhere('email', $request->validated['email'])
        ) {
            return redirect(route('recuperar_senha'));
        }

        notify($usuario, RecuperarSenha::class);

        return redirect(route('login'));
    }

    public function redefinirSenha(Request $request): Response
    {
        if (
            ! $request->validate(Usuario::rules(), ['email', 'expires', 'signature']) ||
            ! hash_equals($request->getUri(), signedRoute('redefinir_senha', $request->validated)) ||
            ! Usuario::outdatedBefore($request->validated['expires'])
                ->where('email', $request->validated['email'])
                ->exists()
        ) {
            return responseError(Response::HTTP_FORBIDDEN);
        }

        return response('cadastro/redefinir_senha');
    }

    public function submitRedefinirSenha(Request $request): RedirectResponse|Response
    {
        if (
            ! $request->validate(Usuario::rules(), ['email', 'expires', 'signature', 'senha']) ||
            ! hash_equals($request->getUri(), signedRoute('redefinir_senha', $request->validated)) ||
            ! $usuario = Usuario::outdatedBefore($request->validated['expires'])
                ->firstWhere('email', $request->validated['email'])
        ) {
            return responseError(Response::HTTP_FORBIDDEN);
        }

        $usuario->update($request->validated);

        notify($usuario, SenhaRedefinida::class);

        return redirect(route('login'));
    }

    public function restaurarSenha(Request $request): RedirectResponse|Response
    {
        if (
            ! $request->validate(Usuario::rules(), ['email', 'expires', 'signature']) ||
            ! hash_equals($request->getUri(), signedRoute('restaurar_senha', $request->validated)) ||
            ! ($usuario = Usuario::firstWhere('email', $request->validated['email'])) ||
            ! ($audit = $usuario->audits()
                ->latest()
                ->whereRaw("json_extract(new_values, '$.senha')=?", $usuario->senha)
                ->first())
        ) {
            return responseError(Response::HTTP_FORBIDDEN);
        }

        if ($audit->old_values) {
            $usuario->update(['senha' => $audit->old_values->senha]);

            notify($usuario, SenhaRestaurada::class);
        }

        return redirect(route('login'));
    }
}
