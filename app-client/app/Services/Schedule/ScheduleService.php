<?php
namespace App\Services\Schedule;

use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleService
{
    public function isOutsideWorkingDays(Carbon $date, $companySettings): bool
    {
        $dayOfWeek = $date->dayOfWeek + 1;
        return $dayOfWeek < $companySettings->working_day_start || $dayOfWeek > $companySettings->working_day_end;
    }

    public function isOutsideWorkingHours($startTime, $endTime, $companySettings): bool
    {
        $startTime = substr($startTime, 0, 5);
        $endTime = substr($endTime, 0, 5);
        $workingHourStart = substr($companySettings->working_hour_start, 0, 5);
        $workingHourEnd = substr($companySettings->working_hour_end, 0, 5);

        return $startTime < $workingHourStart || $endTime > $workingHourEnd;
    }

    public function hasConflict($unitId, $scheduleDate, $startTime, $endTime, $currentScheduleId): bool
    {
        return Schedule::where('unit_id', $unitId)
            ->where('id', '!=', $currentScheduleId)
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
