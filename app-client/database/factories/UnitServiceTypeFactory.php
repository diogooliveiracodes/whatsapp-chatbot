<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnitServiceType>
 */
class UnitServiceTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $serviceTypes = [
            'Consulta',
            'Procedimento',
            'Avaliação',
            'Retorno',
            'Exame',
            'Tratamento',
            'Limpeza',
            'Manutenção',
            'Instalação',
            'Reparo'
        ];

        // Combine a random service type with a random word to ensure uniqueness
        $name = $this->faker->randomElement($serviceTypes) . ' ' . $this->faker->word();

        return [
            'company_id' => Company::factory(),
            'unit_id' => Unit::factory(),
            'name' => $name,
            'description' => $this->faker->optional(0.7)->sentence(),
        ];
    }
}
