<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanySettings>
 */
class CompanySettingsFactory extends Factory
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
            'company_id' => Company::factory(),
            'name' => $this->faker->company,
            'identification' => $this->faker->unique()->uuid,
            'phone' => $this->faker->phoneNumber,
            'whatsapp_webhook_url' => $this->faker->url,
            'whatsapp_number' => $this->faker->phoneNumber,
            'default_language' => $this->faker->randomElement(['en', 'pt', 'es', 'fr', 'de']),
            'timezone' => $this->faker->timezone,
            'working_hour_start' => '8:00:00',
            'working_hour_end' => '18:00:00',
            'working_day_start' => self::MONDAY,
            'working_day_end' => self::FRIDAY,
            'use_ai_chatbot' => $this->faker->boolean,
        ];
    }
}
