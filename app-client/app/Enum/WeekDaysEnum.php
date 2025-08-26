<?php

namespace App\Enum;

enum WeekDaysEnum: string
{
    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';
    case SATURDAY = 'saturday';
    case SUNDAY = 'sunday';

    /**
     * Get all week days
     *
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::MONDAY,
            self::TUESDAY,
            self::WEDNESDAY,
            self::THURSDAY,
            self::FRIDAY,
            self::SATURDAY,
            self::SUNDAY,
        ];
    }

    /**
     * Get week day name
     *
     * @return string
     */
    public function getName(): string
    {
        return match($this) {
            self::MONDAY => __('enums.week_days.monday'),
            self::TUESDAY => __('enums.week_days.tuesday'),
            self::WEDNESDAY => __('enums.week_days.wednesday'),
            self::THURSDAY => __('enums.week_days.thursday'),
            self::FRIDAY => __('enums.week_days.friday'),
            self::SATURDAY => __('enums.week_days.saturday'),
            self::SUNDAY => __('enums.week_days.sunday'),
        };
    }

    /**
     * Get week day short name
     *
     * @return string
     */
    public function getShortName(): string
    {
        return match($this) {
            self::MONDAY => __('enums.week_days.mon'),
            self::TUESDAY => __('enums.week_days.tue'),
            self::WEDNESDAY => __('enums.week_days.wed'),
            self::THURSDAY => __('enums.week_days.thu'),
            self::FRIDAY => __('enums.week_days.fri'),
            self::SATURDAY => __('enums.week_days.sat'),
            self::SUNDAY => __('enums.week_days.sun'),
        };
    }
}
