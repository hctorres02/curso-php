<?php

namespace App\Http;

use App\Models\Usuario;

class Auth
{
    public static function attempt(array $validated): ?Usuario
    {
        $usuario = Usuario::firstWhere('email', $validated['email']);
        $senhaCorreta = $usuario && password_verify($validated['senha'], $usuario->senha);

        if (! $senhaCorreta) {
            flash()->set('err', [
                'email' => 'Email/senha incorretos',
            ]);

            return null;
        }

        if (password_needs_rehash($usuario->senha, constant(env('PASSWORD_ALGO')))) {
            $usuario->forceFill($validated)->save();
            $usuario->refresh();
        }

        session()->set('usuario', $usuario);
        session()->set('usuario_id', $usuario->id);
        session()->migrate(true);

        return $usuario;
    }
}
