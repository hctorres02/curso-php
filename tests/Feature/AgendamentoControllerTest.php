<?php

use App\Http\Http;
use App\Models\Agendamento;
use App\Models\Atividade;
use App\Models\Disciplina;
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

describe('AgendamentoController', function () {
    test('não há agendamentos previstos', function () {
        $response = Http::get('/agendamentos');
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch('/existem 0 agendamentos previstos/i');
        expect($responseContent)->toMatch('/não há agendamentos previstos/i');
    });

    test('cadastrar agendamento', function () {
        $atividade = Atividade::factory()->create();
        $atividade_id = $atividade->id;
        $disciplina = Disciplina::factory()->create();
        $disciplina_id = $disciplina->id;
        $conteudo = faker()->words(20, true);
        $data = faker()->dateTimeBetween('+7 days', '+14 days')->format('Y-m-d');
        $options = [
            'form_params' => compact('atividade_id', 'disciplina_id', 'conteudo', 'data'),
        ];

        $response = Http::post('/agendamentos', $options);
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch('/existem 1 agendamentos previstos/i');
        expect($responseContent)->toMatch("/{$data}/i");
        expect($responseContent)->toMatch("/<strong>{$disciplina->nome}<\/strong>/i");
        expect($responseContent)->toMatch("/<small>{$atividade->nome}<\/small>/i");
        expect($responseContent)->toMatch("/{$conteudo}/i");
    });

    test('editar agendamento', function () {
        $atividade = Atividade::factory()->create();
        $atividade_id = $atividade->id;
        $disciplina = Disciplina::factory()->create();
        $disciplina_id = $disciplina->id;
        $agendamento = Agendamento::factory()->create();
        $conteudo = faker()->words(20, true);
        $data = faker()->dateTimeBetween('+7 days', '+14 days')->format('Y-m-d');
        $options = [
            'form_params' => compact('atividade_id', 'disciplina_id', 'conteudo', 'data'),
        ];

        $response = Http::put("/agendamentos/{$agendamento->id}", $options);
        $responseContent = $response->getBody()->getContents();

        $agendamento->refresh();

        expect($responseContent)->toMatch('/existem 1 agendamentos previstos/i');
        expect($responseContent)->toMatch("/{$data}/i");
        expect($responseContent)->toMatch("/{$agendamento->data}/i");
        expect($responseContent)->toMatch("/<strong>{$disciplina->nome}<\/strong>/i");
        expect($responseContent)->toMatch("/<small>{$atividade->nome}<\/small>/i");
        expect($responseContent)->toMatch("/<strong>{$agendamento->disciplina->nome}<\/strong>/i");
        expect($responseContent)->toMatch("/<small>{$agendamento->atividade->nome}<\/small>/i");
        expect($responseContent)->toMatch("/{$conteudo}/i");
        expect($responseContent)->toMatch("/{$agendamento->conteudo}/i");
    });

    test('excluir agendamento', function () {
        $count = Agendamento::query()->count();
        $agendamento = Agendamento::factory()->create();

        $response = Http::delete("/agendamentos/{$agendamento->id}");
        $responseContent = $response->getBody()->getContents();

        expect($responseContent)->toMatch("/existem {$count} agendamentos previstos/i");
    });
});
