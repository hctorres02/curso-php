<?php

namespace Database\Factories;

use App\Models\Atividade;
use App\Models\Disciplina;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agendamento>
 */
class AgendamentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'disciplina_id' => Disciplina::factory(),
            'atividade_id' => Atividade::factory(),
            'data' => faker()->dateTimeBetween('-6 months', '+6 months'),
            'conteudo' => faker()->text(512),
        ];
    }
}
