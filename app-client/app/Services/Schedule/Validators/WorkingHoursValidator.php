<?php

namespace App\Services\Schedule\Validators;

use App\Services\Schedule\Interfaces\WorkingHoursValidatorInterface;
use Carbon\Carbon;

class WorkingHoursValidator implements WorkingHoursValidatorInterface
{
    public function isOutsideWorkingHours(Carbon $scheduleDate, string $startTime, string $endTime, $unitSettings): bool
    {
        // Helper function to parse time string to Carbon instance
        $parseTime = function($time) {
            // Extract only the time part (HH:mm) from the string
            if (preg_match('/(\d{1,2}:\d{2})/', $time, $matches)) {
                $time = $matches[1];
            }

            // Split hours and minutes and validate
            $parts = explode(':', $time);
            if (count($parts) !== 2) {
                throw new \InvalidArgumentException('Invalid time format. Expected HH:mm');
            }

            list($hours, $minutes) = $parts;

            // Validate hours and minutes
            if (!is_numeric($hours) || !is_numeric($minutes)) {
                throw new \InvalidArgumentException('Hours and minutes must be numeric');
            }

            $hours = (int)$hours;
            $minutes = (int)$minutes;

            if ($hours < 0 || $hours > 23 || $minutes < 0 || $minutes > 59) {
                throw new \InvalidArgumentException('Invalid time values. Hours must be 0-23 and minutes must be 0-59');
            }

            // Create Carbon instance with today's date and the given time
            return Carbon::today()->setHour($hours)->setMinute($minutes);
        };

        try {
            // Get the day of week (1 = Sunday, 7 = Saturday)
            $dayOfWeek = $scheduleDate->dayOfWeek + 1;

            $dayMap = [
                1 => 'sunday',
                2 => 'monday',
                3 => 'tuesday',
                4 => 'wednesday',
                5 => 'thursday',
                6 => 'friday',
                7 => 'saturday'
            ];
            $dayKey = $dayMap[$dayOfWeek];

            // Check if the day is enabled
            if (!$unitSettings->$dayKey) {
                throw new \InvalidArgumentException(__('schedules.messages.outside_working_days'));
            }

            // Get the working hours for the specific day
            $workingHourStart = $unitSettings->{$dayKey . '_start'};
            $workingHourEnd = $unitSettings->{$dayKey . '_end'};

            if (!$workingHourStart || !$workingHourEnd) {
                throw new \InvalidArgumentException(__('schedules.messages.outside_working_hours'));
            }

            // Parse all times
            $startTime = $parseTime($startTime);
            $endTime = $parseTime($endTime);
            $workingHourStart = $parseTime($workingHourStart);
            $workingHourEnd = $parseTime($workingHourEnd);

            // Compare times using Carbon
            return $startTime->lt($workingHourStart) || $endTime->gt($workingHourEnd);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }
}
