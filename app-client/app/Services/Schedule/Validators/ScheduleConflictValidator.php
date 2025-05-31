<?php

namespace App\Services\Schedule\Validators;

use App\Models\Schedule;
use App\Services\Schedule\Interfaces\ScheduleConflictValidatorInterface;

class ScheduleConflictValidator implements ScheduleConflictValidatorInterface
{
    public function hasConflict(int $unitId, string $scheduleDate, string $startTime, string $endTime, ?int $currentScheduleId = null): bool
    {
        return Schedule::where('unit_id', $unitId)
            ->when($currentScheduleId, function ($query) use ($currentScheduleId) {
                return $query->where('id', '!=', $currentScheduleId);
            })
            ->where('schedule_date', $scheduleDate)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime) {
                    $q->where('start_time', '<=', $startTime)
                        ->where('end_time', '>', $startTime);
                })->orWhere(function ($q) use ($endTime) {
                    $q->where('start_time', '<', $endTime)
                        ->where('end_time', '>=', $endTime);
                });
            })->exists();
    }
}
