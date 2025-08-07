<?php

namespace Database\Factories;

use App\Enum\ScheduleBlockTypeEnum;
use App\Models\ScheduleBlock;
use App\Models\Company;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleBlockFactory extends Factory
{
    protected $model = ScheduleBlock::class;

        public function definition(): array
    {
        $blockType = $this->faker->randomElement(ScheduleBlockTypeEnum::cases());

        return [
            'company_id' => Company::factory(),
            'unit_id' => Unit::factory(),
            'user_id' => User::factory(),
            'block_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'start_time' => $blockType === ScheduleBlockTypeEnum::TIME_SLOT ? $this->faker->time('H:i') : null,
            'end_time' => $blockType === ScheduleBlockTypeEnum::TIME_SLOT ? $this->faker->time('H:i') : null,
            'block_type' => $blockType,
            'reason' => $this->faker->optional()->sentence(),
            'active' => true,
        ];
    }

    public function timeSlot(): static
    {
        return $this->state(fn (array $attributes) => [
            'block_type' => ScheduleBlockTypeEnum::TIME_SLOT,
            'start_time' => $this->faker->time('H:i'),
            'end_time' => $this->faker->time('H:i'),
        ]);
    }

    public function fullDay(): static
    {
        return $this->state(fn (array $attributes) => [
            'block_type' => ScheduleBlockTypeEnum::FULL_DAY,
            'start_time' => null,
            'end_time' => null,
        ]);
    }
}
