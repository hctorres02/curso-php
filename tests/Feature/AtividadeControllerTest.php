<?php

use App\Models\Atividade;
use Tests\TestCase;

uses(TestCase::class);

beforeAll(function () {
    refreshDatabase();
    runServer();
});

afterAll(function () {
    stopServer();
});

describe('AtividadeController', function () {
    test('não há atividades', function () {
        $response = httpClient()->get('/atividades');
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch('/total: 0 atividades/i');
        expect($responseContent)->toMatch('/não há atividades/i');
    });

    test('cadastrar atividade', function () {
        $nome = faker()->word();
        $cor = faker()->hexColor();
        $options = [
            'form_params' => compact('nome', 'cor'),
        ];

        $response = httpClient()->post('/atividades', $options);
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch('/total: 1 atividades/i');
        expect($responseContent)->toMatch("/{$nome}/i");
    });

    test('editar atividade', function () {
        $atividade = Atividade::factory()->create();
        $nome = faker()->word();
        $cor = faker()->hexColor();
        $options = [
            'form_params' => compact('nome', 'cor'),
        ];

        $response = httpClient()->put("/atividades/{$atividade->id}", $options);
        $responseContent = $response->getBody()->getContents();

        $atividade->refresh();

        expect($responseContent)->toMatch('/total: 2 atividades/i');
        expect($responseContent)->toMatch("/{$nome}/i");
        expect($responseContent)->toMatch("/{$atividade->nome}/i");
    });

    test('excluir atividade', function () {
        $count = Atividade::query()->count();
        $atividade = Atividade::factory()->create();

        $response = httpClient()->delete("/atividades/{$atividade->id}");
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch("/total: {$count} atividades/i");
    });
});
