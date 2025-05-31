<?php

namespace App\Services\Schedule\Validators;

use App\Services\Schedule\Interfaces\WorkingDaysValidatorInterface;
use Carbon\Carbon;

class WorkingDaysValidator implements WorkingDaysValidatorInterface
{
    public function isOutsideWorkingDays(Carbon $date, $unitSettings): bool
    {
        $dayOfWeek = $date->dayOfWeek + 1;
        return $dayOfWeek < $unitSettings->working_day_start || $dayOfWeek > $unitSettings->working_day_end;
    }
}
