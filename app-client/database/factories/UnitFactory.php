<?php

namespace Database\Factories;

use Database\Seeders\DataMocks;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    const ACTIVE = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->city(),
            'company_id' => DataMocks::getCompanyId(),
            'active' => self::ACTIVE
        ];
    }
}
