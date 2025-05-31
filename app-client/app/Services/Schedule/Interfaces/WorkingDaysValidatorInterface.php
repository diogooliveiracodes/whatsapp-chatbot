<?php

namespace App\Services\Schedule\Interfaces;

use Carbon\Carbon;

interface WorkingDaysValidatorInterface
{
    public function isOutsideWorkingDays(Carbon $date, $companySettings): bool;
}
