<?php

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Agendamento;
use App\Models\Atividade;
use App\Models\Disciplina;
use App\Models\Periodo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;

uses(TestCase::class);
beforeAll(fn() => refreshDatabase());

describe('model', function () {
    test('possui migration', function (string $className) {
        /** @var Model */
        $model = new $className;
        $table = $model->getTable();
        $migration = glob(PROJECT_ROOT . "/database/migrations/*_create_{$table}_table.php");
        $hasTable = DB::schema()->hasTable($table);

        expect($migration)->toHaveCount(1);
        expect($hasTable)->toBeTrue();
    });

    test('possui propriedades correspondentes', function (string $className, array $properties) {
        /** @var Model */
        $model = new $className;
        $table = $model->getTable();
        $columns = DB::connection()->getSchemaBuilder()->getColumnListing($table);
        $intersect = array_intersect($properties, $columns);

        expect($properties)->toEqualCanonicalizing($intersect);
    });

    test('possui factory', function (string $className) {
        /** @var Model */
        $model = new $className;
        $table = $model->getTable();
        $exists = class_exists(Factory::resolveFactoryName($className));
        $countEmpty = DB::table($table)->count();
        $countOne = $model->factory()->create()->count();

        expect($exists)->toBeTrue();
        expect($countEmpty)->toEqual(0);
        expect($countOne)->toEqual(1);
    });
})->with([
    [Periodo::class, ['id', 'ano', 'semestre']],
    [Disciplina::class, ['id', 'periodo_id', 'nome', 'cor']],
    [Atividade::class, ['id', 'nome', 'cor']],
    [Agendamento::class, ['id', 'disciplina_id', 'atividade_id', 'data', 'conteudo']],
]);
