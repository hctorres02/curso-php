<?php

use App\Http\Http;
use App\Models\Usuario;
use Tests\TestCase;

uses(TestCase::class);

beforeAll(function () {
    runServer();
});

beforeEach(function () {
    refreshDatabase();

    $senha = faker()->password(8);
    $email = faker()->safeEmail();
    $usuario = Usuario::factory()->create(compact('email', 'senha'));
    $_csrf_token = Http::getCsrfToken('/login');

    Http::post('/login', [
        'form_params' => compact('email', 'senha', '_csrf_token'),
    ]);
});

afterAll(function () {
    stopServer();
});

describe('UsuarioController', function () {
    test('não há usuários cadastrados', function () {
        $response = Http::get('/usuarios');
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch('/total: 0 usuários/i');
        expect($responseContent)->toMatch('/não há usuários cadastrados/i');
    });

    test('cadastrar usuario', function () {
        $nome = faker()->firstName();
        $email = faker()->safeEmail();
        $senha = faker()->password(8);
        $_csrf_token = Http::getCsrfToken('/usuarios/cadastrar');
        $options = [
            'form_params' => compact('nome', 'email', 'senha', '_csrf_token'),
        ];

        $response = Http::post('/usuarios', $options);
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch("/{$nome}/i");
        expect($responseContent)->toMatch("/{$email}/i");
    });

    test('editar usuario', function () {
        $nome = faker()->firstName();
        $email = faker()->safeEmail();
        $senha = faker()->password(8);
        $usuario = Usuario::factory()->create(compact('email', 'senha'));
        $_csrf_token = Http::getCsrfToken("/usuarios/{$usuario->id}/editar");
        $options = [
            'form_params' => compact('nome', 'email', '_csrf_token'),
        ];

        $response = Http::put("/usuarios/{$usuario->id}", $options);
        $responseContent = $response->getBody()->getContents();

        $usuario->refresh();

        expect($responseContent)->toMatch("/{$nome}/i");
        expect($responseContent)->toMatch("/{$usuario->nome}/i");
        expect($responseContent)->toMatch("/{$email}/i");
        expect($responseContent)->toMatch("/{$usuario->email}/i");
    });
});
