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
});

afterAll(function () {
    stopServer();
});

describe('CadastroController', function () {
    test('cadastrar perfil', function () {
        $nome = faker()->firstName();
        $email = faker()->safeEmail();
        $senha = faker()->password(8);
        $_csrf_token = Http::getCsrfToken('/cadastro');
        $options = [
            'form_params' => compact('nome', 'email', 'senha', '_csrf_token'),
        ];

        Http::post('/cadastro', $options);
        Http::post('/login', $options);

        $response = Http::get('/cadastro/editar');
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch("/{$nome}/i");
        expect($responseContent)->toMatch("/{$email}/i");
    });

    test('editar perfil', function () {
        $nome = faker()->firstName();
        $email = faker()->safeEmail();
        $senha = faker()->password(8);
        $usuario = Usuario::factory()->create(compact('senha'));
        $_csrf_token = Http::getCsrfToken('/login');
        $options = [
            'form_params' => compact('nome', 'email', '_csrf_token'),
        ];

        Http::post('/login', [
            'form_params' => [...compact('senha', '_csrf_token'), 'email' => $usuario->email],
        ]);

        $response = Http::put('/cadastro/editar', $options);
        $responseContent = $response->getBody()->getContents();

        $usuario->refresh();

        expect($responseContent)->toMatch("/{$nome}/i");
        expect($responseContent)->toMatch("/{$usuario->nome}/i");
        expect($responseContent)->toMatch("/{$email}/i");
        expect($responseContent)->toMatch("/{$usuario->email}/i");
    });
});
