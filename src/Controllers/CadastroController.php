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
        return response('cadastro/cadastrar.twig');
    }

    public function salvar(Request $request): RedirectResponse
    {
        if (! $request->validate(Usuario::rules(), ['nome', 'email', 'senha'])) {
            return redirectRoute('cadastro');
        }

        $usuario = Usuario::create($request->validated);

        notify($usuario, UsuarioCadastrado::class);

        return redirectRoute('login');
    }

    public function editar(): Response
    {
        $usuario = session()->get('usuario');

        return response('cadastro/editar.twig', compact('usuario'));
    }

    public function atualizar(Request $request): RedirectResponse
    {
        if (! $request->validate(Usuario::rules(), ['nome', 'email'])) {
            return redirectRoute('editar_cadastro');
        }

        session()->get('usuario')->update($request->validated);
        session()->migrate(true);

        return redirectRoute('editar_cadastro');
    }

    public function recuperarSenha(): Response
    {
        return response('cadastro/recuperar_senha.twig');
    }

    public function submitRecuperarSenha(Request $request): RedirectResponse
    {
        if (
            ! $request->validate(Usuario::rules(), ['email']) ||
            ! $usuario = Usuario::firstWhere([['id', '!=', 1], ['email', $request->validated['email']]])
        ) {
            flash()->setAll([
                'err' => ['email' => null],
                'old' => ['email' => $request->get('email')],
            ]);

            return redirectRoute('recuperar_senha');
        }

        notify($usuario, RecuperarSenha::class);

        return redirectRoute('login');
    }

    public function redefinirSenha(Request $request): Response
    {
        if (
            ! $request->validateSignedRoute('redefinir_senha', Usuario::rules(), ['email']) ||
            ! Usuario::outdatedBefore($request->validated['expires'])
                ->where([['id', '!=', 1], ['email', $request->validated['email']]])
                ->exists()
        ) {
            return responseError(Response::HTTP_FORBIDDEN);
        }

        return response('cadastro/redefinir_senha.twig');
    }

    public function submitRedefinirSenha(Request $request): RedirectResponse|Response
    {
        if (
            ! $request->validateSignedRoute('redefinir_senha', Usuario::rules(), ['email', 'senha']) ||
            ! $usuario = Usuario::outdatedBefore($request->validated['expires'])
                ->firstWhere([['id', '!=', 1], ['email', $request->validated['email']]])
        ) {
            return responseError(Response::HTTP_FORBIDDEN);
        }

        $usuario->update($request->validated);

        notify($usuario, SenhaRedefinida::class);

        return redirectRoute('login');
    }

    public function restaurarSenha(Request $request): RedirectResponse|Response
    {
        if (
            ! $request->validateSignedRoute('restaurar_senha', Usuario::rules(), ['email']) ||
            ! ($usuario = Usuario::firstWhere([['id', '!=', 1], ['email', $request->validated['email']]])) ||
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

        return redirectRoute('login');
    }
}
