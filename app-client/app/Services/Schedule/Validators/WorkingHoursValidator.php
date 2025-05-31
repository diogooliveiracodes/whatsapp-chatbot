<?php

namespace App\Services\Schedule\Validators;

use App\Services\Schedule\Interfaces\WorkingHoursValidatorInterface;
use Carbon\Carbon;

class WorkingHoursValidator implements WorkingHoursValidatorInterface
{
    public function isOutsideWorkingHours(string $startTime, string $endTime, $unitSettings): bool
    {
        // Helper function to parse time string to Carbon instance
        $parseTime = function($time) {
            // Extract only the time part (HH:mm) from the string
            if (preg_match('/(\d{1,2}:\d{2})/', $time, $matches)) {
                $time = $matches[1];
            }

            // Split hours and minutes
            list($hours, $minutes) = explode(':', $time);

            // Create Carbon instance with today's date and the given time
            return Carbon::today()->setHour((int)$hours)->setMinute((int)$minutes);
        };

        // Parse all times
        $startTime = $parseTime($startTime);
        $endTime = $parseTime($endTime);
        $workingHourStart = $parseTime($unitSettings->working_hour_start);
        $workingHourEnd = $parseTime($unitSettings->working_hour_end);

        // Compare times using Carbon
        return $startTime->lt($workingHourStart) || $endTime->gt($workingHourEnd);
    }
}
