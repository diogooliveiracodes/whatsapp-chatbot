<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'active' => $this->faker->boolean(90), // 90% de chance de ser true
            'content' => $this->faker->sentence(),
            'type' => 'text',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
