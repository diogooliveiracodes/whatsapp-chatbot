<?php

namespace App\Services\Schedule\Interfaces;

interface ScheduleConflictValidatorInterface
{
    public function hasConflict(int $unitId, string $scheduleDate, string $startTime, string $endTime, ?int $currentScheduleId = null): bool;
}
