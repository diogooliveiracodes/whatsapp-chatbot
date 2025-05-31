<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnitSettings>
 */
class UnitSettingsFactory extends Factory
{
    const MONDAY = 1;
    const FRIDAY = 6;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(),
            'unit_id' => \App\Models\Unit::factory(),
            'name' => $this->faker->company,
            'phone' => $this->faker->phoneNumber,
            'street' => $this->faker->streetName,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->optional()->secondaryAddress,
            'neighborhood' => $this->faker->citySuffix,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
            'zipcode' => $this->faker->postcode,
            'whatsapp_webhook_url' => $this->faker->url,
            'whatsapp_number' => $this->faker->phoneNumber,
            'working_hour_start' => '08:00:00',
            'working_hour_end' => '18:00:00',
            'working_day_start' => self::MONDAY,
            'working_day_end' => self::FRIDAY,
            'use_ai_chatbot' => $this->faker->boolean,
        ];
    }
}
