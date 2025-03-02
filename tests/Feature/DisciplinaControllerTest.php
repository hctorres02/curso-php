<?php

use App\Http\Http;
use App\Models\Disciplina;
use App\Models\Periodo;
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

describe('DisciplinaController', function () {
    test('não há disciplinas', function () {
        $response = Http::get('/disciplinas');
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch('/total: 0 disciplinas/i');
        expect($responseContent)->toMatch('/não há disciplinas cadastradas/i');
    });

    test('cadastrar disciplina', function () {
        $periodo = Periodo::factory()->create();
        $periodo_id = $periodo->id;
        $nome = faker()->word();
        $cor = faker()->hexColor();
        $_csrf_token = Http::getCsrfToken('/disciplinas/cadastrar');
        $options = [
            'form_params' => compact('periodo_id', 'nome', 'cor', '_csrf_token'),
        ];

        $response = Http::post('/disciplinas', $options);
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch('/total: 1 disciplinas/i');
        expect($responseContent)->toMatch("/{$nome}/i");
        expect($responseContent)->toMatch("/{$periodo->ano}\.{$periodo->semestre}/");
    });

    test('editar disciplina', function () {
        $disciplina = Disciplina::factory()->create();
        $periodo = Periodo::factory()->create();
        $periodo_id = $periodo->id;
        $nome = faker()->word();
        $cor = faker()->hexColor();
        $_csrf_token = Http::getCsrfToken('/disciplinas/cadastrar');
        $options = [
            'form_params' => compact('periodo_id', 'nome', 'cor', '_csrf_token'),
        ];

        $response = Http::put("/disciplinas/{$disciplina->id}", $options);
        $responseContent = $response->getBody()->getContents();

        $disciplina->refresh();

        expect($responseContent)->toMatch('/total: 1 disciplinas/i');
        expect($responseContent)->toMatch("/{$nome}/i");
        expect($responseContent)->toMatch("/{$disciplina->nome}/i");
        expect($responseContent)->toMatch("/{$periodo->ano}\.{$periodo->semestre}/");
    });

    test('excluir disciplina', function () {
        $count = Disciplina::query()->count();
        $disciplina = Disciplina::factory()->create();
        $_csrf_token = Http::getCsrfToken('/disciplinas/cadastrar');
        $options = [
            'form_params' => compact('_csrf_token'),
        ];

        $response = Http::delete("/disciplinas/{$disciplina->id}", $options);
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch("/total: {$count} disciplinas/i");
    });
});
