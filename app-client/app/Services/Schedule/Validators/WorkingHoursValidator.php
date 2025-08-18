<?php

namespace App\Services\Schedule\Validators;

use App\Services\Schedule\Interfaces\WorkingHoursValidatorInterface;
use Carbon\Carbon;
use App\Helpers\TimezoneHelper;

class WorkingHoursValidator implements WorkingHoursValidatorInterface
{
    public function isOutsideWorkingHours(Carbon $scheduleDate, string $startTime, string $endTime, $unitSettings): bool
    {
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

            // Get user timezone
            $userTimezone = $unitSettings->timezone ?? 'America/Sao_Paulo';

            // Parse the schedule times (these are already in user timezone)
            $startTimeCarbon = Carbon::parse($startTime);
            $endTimeCarbon = Carbon::parse($endTime);

            // Parse working hours (these are stored in UTC, need to convert to user timezone)
            $referenceDate = $scheduleDate->format('Y-m-d');

            // Convert working hours from UTC to user timezone using the helper
            $workingHourStartLocal = TimezoneHelper::convertTimeFromUtc($workingHourStart, $userTimezone, $referenceDate);
            $workingHourEndLocal = TimezoneHelper::convertTimeFromUtc($workingHourEnd, $userTimezone, $referenceDate);

            // Create Carbon instances for comparison using the same date
            $startTimeForComparison = Carbon::parse($referenceDate . ' ' . $startTimeCarbon->format('H:i'), $userTimezone);
            $endTimeForComparison = Carbon::parse($referenceDate . ' ' . $endTimeCarbon->format('H:i'), $userTimezone);

            $workingHourStartForComparison = Carbon::parse($referenceDate . ' ' . $workingHourStartLocal, $userTimezone);
            $workingHourEndForComparison = Carbon::parse($referenceDate . ' ' . $workingHourEndLocal, $userTimezone);

            // Compare times using Carbon
            // The schedule is outside working hours if:
            // - Start time is before working hour start, OR
            // - End time is after working hour end
            return $startTimeForComparison->lt($workingHourStartForComparison) || $endTimeForComparison->gt($workingHourEndForComparison);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }
}
