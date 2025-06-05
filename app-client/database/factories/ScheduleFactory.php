<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Customer;
use App\Models\User;
use App\Models\Unit;
use App\Models\UnitServiceType;
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

        // Generate a random hour between 8 and 17 (8 AM to 5 PM)
        $hour = $this->faker->numberBetween(8, 17);
        // Randomly choose between full hour (0) or half hour (30)
        $minute = $this->faker->randomElement([0, 30]);

        $startTime = Carbon::parse($scheduleDate)->setTime($hour, $minute);
        // End time is always 30 minutes after start time
        $endTime = (clone $startTime)->addMinutes(30);

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
            'unit_service_type_id' => UnitServiceType::factory(),
            'schedule_date' => $scheduleDate->format('Y-m-d'),
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
            'notes' => $this->faker->optional(0.7)->sentence(),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'is_confirmed' => $this->faker->boolean(70),
        ];
    }
}
