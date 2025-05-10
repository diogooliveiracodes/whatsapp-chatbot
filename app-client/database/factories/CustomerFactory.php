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
            'type' => $this->faker->randomElement([CustomerTypeEnum::INDIVIDUAL, CustomerTypeEnum::COMPANY]),
            'name' => $this->faker->name(),
            'document_number' => $this->faker->unique()->numerify('###########'),
            'phone' => $this->faker->phoneNumber(),
            'zip_code' => $this->faker->postcode(),
            'state' => $this->faker->stateAbbr(),
            'city' => $this->faker->city(),
            'neighborhood' => $this->faker->streetName(),
            'street' => $this->faker->streetAddress(),
            'number' => $this->faker->buildingNumber(),
            'complement' => $this->faker->word(),
            'prospect_origin' => $this->faker->word(),
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
