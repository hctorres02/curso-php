<?php

use App\Models\Periodo;
use Tests\TestCase;

uses(TestCase::class);

beforeAll(function () {
    refreshDatabase();
    runServer();
});

afterAll(function () {
    stopServer();
});

describe('PeriodoController', function () {
    test('não há períodos cadastrados', function () {
        $response = httpClient()->get('/periodos');
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch('/total: 0 períodos/i');
        expect($responseContent)->toMatch('/não há períodos cadastrados/i');
    });

    test('cadastrar período', function () {
        $ano = faker()->numberBetween(2040, 2045);
        $semestre = faker()->numberBetween(1, 4);
        $options = [
            'form_params' => compact('ano', 'semestre'),
        ];

        $response = httpClient()->post('/periodos', $options);
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch('/total: 1 períodos/i');
        expect($responseContent)->toMatch("/<strong>{$ano}\.{$semestre}<\/strong>/");
    });

    test('editar período', function () {
        $periodo = Periodo::factory()->create();
        $ano = faker()->numberBetween(2050, 2055);
        $semestre = faker()->numberBetween(1, 4);
        $options = [
            'form_params' => compact('ano', 'semestre'),
        ];

        $response = httpClient()->put("/periodos/{$periodo->id}", $options);
        $responseContent = $response->getBody()->getContents();

        $periodo->refresh();

        expect($responseContent)->toMatch('/total: 2 períodos/i');
        expect($responseContent)->toMatch("/<strong>{$ano}\.{$semestre}<\/strong>/");
        expect($responseContent)->toMatch("/<strong>{$periodo->ano}\.{$periodo->semestre}<\/strong>/");
    });

    test('excluir período', function () {
        $count = Periodo::query()->count();
        $periodo = Periodo::factory()->create();

        $response = httpClient()->delete("/periodos/{$periodo->id}");
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch("/total: {$count} períodos/i");
    });
});
