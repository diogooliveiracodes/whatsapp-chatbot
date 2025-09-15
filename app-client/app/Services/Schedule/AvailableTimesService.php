<?php

namespace App\Services\Schedule;

use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AvailableTimesService
{
    public function __construct(
        private ScheduleService $scheduleService,
        private ScheduleBlockService $scheduleBlockService,
        private ScheduleTimeService $scheduleTimeService,
    ) {}

    /**
     * Compute available times for a given unit and local date (YYYY-MM-DD).
     * Returns a collection of strings in H:i format in the unit's local timezone.
     */
    public function getAvailableTimesForDate(Unit $unit, string $date): Collection
    {
        $unit->loadMissing('unitSettings');
        $unitSettings = $unit->unitSettings;

        if (!$date) {
            return collect();
        }

        $duration = (int) ($unitSettings->appointment_duration_minutes ?? 30);

        $dayOfWeek = Carbon::parse($date)->dayOfWeek; // 0..6
        $dayKeyMap = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
        $dayKey = $dayKeyMap[$dayOfWeek] ?? 'monday';

        $slots = $this->scheduleService
            ->getAvailableTimeSlots(Carbon::parse($date), $unitSettings)
            ->filter(function (Carbon $localStart) use ($date, $dayKey, $unit, $unitSettings, $duration) {
                // Skip past times for today in unit timezone
                $nowLocal = now($unitSettings->timezone ?? 'America/Sao_Paulo');
                if ($date === $nowLocal->format('Y-m-d') && $localStart->lte($nowLocal)) {
                    return false;
                }

                // Inside break period?
                if ($this->scheduleTimeService->isInsideBreakPeriod($localStart, $dayKey, $unitSettings, $duration)) {
                    return false;
                }

                // Convert to UTC for conflict and block checks
                $tz = $unitSettings->timezone ?? 'America/Sao_Paulo';
                $startLocal = Carbon::parse($date . ' ' . $localStart->format('H:i'), $tz);
                $startUtc = $startLocal->copy()->setTimezone('UTC');
                $endUtc = $startLocal->copy()->addMinutes($duration)->setTimezone('UTC');

                if ($this->scheduleService->hasConflict(
                    $unit->id,
                    $startUtc->format('Y-m-d'),
                    $startUtc->format('H:i'),
                    $endUtc->format('H:i'),
                    null
                )) {
                    return false;
                }

                if ($this->scheduleBlockService->isTimeSlotBlocked(
                    $unit->id,
                    $startUtc->format('Y-m-d'),
                    $startUtc->format('H:i'),
                    $endUtc->format('H:i')
                )) {
                    return false;
                }

                return true;
            })
            ->values()
            ->map(fn (Carbon $t) => $t->format('H:i'));

        return $slots;
    }
}


