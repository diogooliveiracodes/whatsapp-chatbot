<?php

namespace App\Enum;

class DaysOfWeekEnum
{
    public static function getDaysOfWeek()
    {
        return [
            'sunday' => __('unitSettings.sunday'),
            'monday' => __('unitSettings.monday'),
            'tuesday' => __('unitSettings.tuesday'),
            'wednesday' => __('unitSettings.wednesday'),
            'thursday' => __('unitSettings.thursday'),
            'friday' => __('unitSettings.friday'),
            'saturday' => __('unitSettings.saturday'),
        ];
    }
}