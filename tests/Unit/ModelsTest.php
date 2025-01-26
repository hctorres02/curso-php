<?php

use Models\Agendamento;
use Models\Atividade;
use Models\Disciplina;
use Models\Periodo;

describe('model', function () {
    test('possui propriedades correspondentes', function (string $className, array $properties) {
        /** @var Model */
        $model = new $className;

        expect($model)->toHaveProperties($properties);
    });
})->with([
    [Periodo::class, ['id', 'ano', 'semestre']],
    [Disciplina::class, ['id', 'periodo_id', 'nome', 'cor']],
    [Atividade::class, ['id', 'nome', 'cor']],
    [Agendamento::class, ['id', 'disciplina_id', 'atividade_id', 'data', 'conteudo']],
]);
