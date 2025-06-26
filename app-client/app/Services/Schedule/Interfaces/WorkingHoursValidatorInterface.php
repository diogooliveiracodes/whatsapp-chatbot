<?php

namespace App\Services\Schedule\Interfaces;

use Carbon\Carbon;

interface WorkingHoursValidatorInterface
{
    public function isOutsideWorkingHours(Carbon $scheduleDate, string $startTime, string $endTime, $unitSettings): bool;
}
