<?php

namespace App\Services\Schedule\Validators;

use App\Services\Schedule\Interfaces\WorkingHoursValidatorInterface;

class WorkingHoursValidator implements WorkingHoursValidatorInterface
{
    public function isOutsideWorkingHours(string $startTime, string $endTime, $companySettings): bool
    {
        $startTime = substr($startTime, 0, 5);
        $endTime = substr($endTime, 0, 5);
        $workingHourStart = substr($companySettings->working_hour_start, 0, 5);
        $workingHourEnd = substr($companySettings->working_hour_end, 0, 5);

        return $startTime < $workingHourStart || $endTime > $workingHourEnd;
    }
}
