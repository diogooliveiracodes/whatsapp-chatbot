<?php

namespace App\Services\Schedule\Validators;

use App\Services\Schedule\Interfaces\WorkingDaysValidatorInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WorkingDaysValidator implements WorkingDaysValidatorInterface
{
    public function isOutsideWorkingDays(Carbon $date, $unitSettings): bool
    {
        $dayOfWeek = $date->dayOfWeek + 1;
        $workingDays = [
            1 => $unitSettings->sunday,
            2 => $unitSettings->monday,
            3 => $unitSettings->tuesday,
            4 => $unitSettings->wednesday,
            5 => $unitSettings->thursday,
            6 => $unitSettings->friday,
            7 => $unitSettings->saturday
        ];

        return !$workingDays[$dayOfWeek];
    }
}
