<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatSession>
 */
class ChatSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'closed_by' => null,
            'closed_at' => null,
            'active' => $this->faker->boolean(90), // 90% de chance de ser true
            'channel' => Str::uuid(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
