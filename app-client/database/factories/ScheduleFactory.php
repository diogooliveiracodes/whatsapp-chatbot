<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Customer;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

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
        $scheduleDate = $this->faker->dateTimeBetween('now', '+2 months');
        $startTime = Carbon::parse($scheduleDate)->setTimeFromTimeString($this->faker->time('H:i'));
        $endTime = (clone $startTime)->addHours($this->faker->numberBetween(1, 4));

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
            'schedule_date' => $scheduleDate->format('Y-m-d'),
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
            'service_type' => $this->faker->randomElement($serviceTypes),
            'notes' => $this->faker->optional(0.7)->sentence(),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed']),
            'is_confirmed' => $this->faker->boolean(70),
        ];
    }
}
