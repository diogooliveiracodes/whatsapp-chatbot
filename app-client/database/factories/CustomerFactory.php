<?php

namespace Database\Factories;

use App\Enum\CustomerTypeEnum;
use Database\Seeders\DataMocks;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'active' => $this->faker->boolean(),
            'company_id' => DataMocks::getCompanyId(),
            'user_id' => $this->faker->randomElement(DataMocks::getEmployeesIdList()),
            'unit_id' => $this->faker->randomNumber(1),
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
