<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnitSettings>
 */
class UnitSettingsFactory extends Factory
{
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
            'sunday_start' => null,
            'sunday_end' => null,
            'sunday' => false,
            'monday_start' => '08:00:00',
            'monday_end' => '18:00:00',
            'monday' => true,
            'tuesday_start' => '08:00:00',
            'tuesday_end' => '18:00:00',
            'tuesday' => true,
            'wednesday_start' => '08:00:00',
            'wednesday_end' => '18:00:00',
            'wednesday' => true,
            'thursday_start' => '08:00:00',
            'thursday_end' => '18:00:00',
            'thursday' => true,
            'friday_start' => '08:00:00',
            'friday_end' => '18:00:00',
            'friday' => true,
            'saturday_start' => null,
            'saturday_end' => null,
            'saturday' => false,
            'use_ai_chatbot' => $this->faker->boolean,
        ];
    }
}
