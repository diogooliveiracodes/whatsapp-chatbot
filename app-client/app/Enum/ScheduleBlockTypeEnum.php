<?php

namespace App\Enum;

enum ScheduleBlockTypeEnum: string
{
    case TIME_SLOT = 'time_slot';
    case FULL_DAY = 'full_day';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return match($this) {
            self::TIME_SLOT => __('schedule-blocks.types.time_slot'),
            self::FULL_DAY => __('schedule-blocks.types.full_day'),
        };
    }
}
