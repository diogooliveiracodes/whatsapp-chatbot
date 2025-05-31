<?php

namespace App\Services\Schedule\Interfaces;

interface WorkingHoursValidatorInterface
{
    public function isOutsideWorkingHours(string $startTime, string $endTime, $companySettings): bool;
}
