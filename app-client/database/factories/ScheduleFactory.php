<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Customer;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('now', '+2 months');
        $endTime = clone $startTime;
        $endTime->modify('+' . $this->faker->numberBetween(1, 4) . ' hours');

        $serviceTypes = [
            'Consulta',
            'Procedimento',
            'Avaliação',
            'Retorno',
            'Exame',
            'Tratamento'
        ];

        return [
            'unit_id' => Unit::factory(),
            'user_id' => User::factory(),
            'customer_id' => Customer::factory(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'service_type' => $this->faker->randomElement($serviceTypes),
            'notes' => $this->faker->optional(0.7)->sentence(),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed']),
            'is_confirmed' => $this->faker->boolean(70),
        ];
    }
}
