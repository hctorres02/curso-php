<?php

use App\Models\Agendamento;
use App\Models\Atividade;
use App\Models\Disciplina;
use App\Models\Periodo;

describe('model', function () {
    test('possui propriedades correspondentes', function (string $className, array $properties) {
        /** @var Model */
        $model = new $className;
        $table = $model->getTable();
        $columns = DB::connection()->getSchemaBuilder()->getColumnListing($table);
        $intersect = array_intersect($properties, $columns);

        expect($properties)->toEqualCanonicalizing($intersect);
    });
})->with([
    [Periodo::class, ['id', 'ano', 'semestre']],
    [Disciplina::class, ['id', 'periodo_id', 'nome', 'cor']],
    [Atividade::class, ['id', 'nome', 'cor']],
    [Agendamento::class, ['id', 'disciplina_id', 'atividade_id', 'data', 'conteudo']],
]);
