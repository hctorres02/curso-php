<?php

namespace Database\Factories;

use App\Models\Periodo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Disciplina>
 */
class DisciplinaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'periodo_id' => Periodo::factory(),
            'nome' => faker()->unique()->firstName(),
            'cor' => faker()->safeColorName(),
        ];
    }
}
