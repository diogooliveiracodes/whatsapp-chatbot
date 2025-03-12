<?php

namespace Database\Factories;

use App\Enum\CustomerTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    const EMPLOYEE_USERS_IDS = [3, 4, 5];
    const FIRST_COMPANY_ID = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'active' => $this->faker->boolean(),
            'company_id' => self::FIRST_COMPANY_ID,
            'user_id' => $this->faker->randomElement(self::EMPLOYEE_USERS_IDS),
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
