<?php

namespace App\Services\Schedule;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class ScheduleTimeService
{
    /**
     * Calculate working hours for a specific day
     *
     * @param string $dayKey
     * @param object $unitSettings
     * @return array{startTime: Carbon, endTime: Carbon}
     */
    public function calculateWorkingHoursForDay(string $dayKey, $unitSettings): array
    {
        $isDayEnabled = $unitSettings->$dayKey;
        $dayStartTime = $unitSettings->{$dayKey . '_start'};
        $dayEndTime = $unitSettings->{$dayKey . '_end'};

        // Se o dia não está habilitado, retorna horários vazios
        if (!$isDayEnabled) {
            return [
                'startTime' => null,
                'endTime' => null
            ];
        }

        // Se não há horários configurados, usa horários padrão
        if (!$dayStartTime && !$dayEndTime) {
            return [
                'startTime' => Carbon::createFromTime(8, 0, 0),
                'endTime' => Carbon::createFromTime(18, 0, 0)
            ];
        }

        // Se apenas um dos horários está configurado, usa o padrão para o outro
        if (!$dayStartTime) {
            $dayStartTime = '08:00';
        }
        if (!$dayEndTime) {
            $dayEndTime = '18:00';
        }

        // Converter de UTC para o fuso do usuário (considerando a data de hoje, pois é apenas hora do dia)
        $userTimezone = $unitSettings->timezone ?? 'UTC';
        $referenceDate = now()->format('Y-m-d');
        $startLocal = Carbon::parse($referenceDate . ' ' . $dayStartTime, 'UTC')->setTimezone($userTimezone)->format('H:i');
        $endLocal = Carbon::parse($referenceDate . ' ' . $dayEndTime, 'UTC')->setTimezone($userTimezone)->format('H:i');

        return [
            'startTime' => Carbon::parse($startLocal),
            'endTime' => Carbon::parse($endLocal)
        ];
    }

    /**
     * Calculate break period for a specific day in user timezone
     *
     * @param string $dayKey
     * @param object $unitSettings
     * @param Carbon $referenceDate Date to anchor time conversion
     * @return array{startTime: Carbon|null, endTime: Carbon|null}
     */
    public function calculateBreakForDay(string $dayKey, $unitSettings, Carbon $referenceDate): array
    {
        $hasBreak = (bool) ($unitSettings->{$dayKey . '_has_break'} ?? false);
        $breakStartUtc = $unitSettings->{$dayKey . '_break_start'} ?? null;
        $breakEndUtc = $unitSettings->{$dayKey . '_break_end'} ?? null;

        if (!$hasBreak || !$breakStartUtc || !$breakEndUtc) {
            return [
                'startTime' => null,
                'endTime' => null,
            ];
        }

        $userTimezone = $unitSettings->timezone ?? 'UTC';
        $dateString = $referenceDate->format('Y-m-d');

        $startLocal = Carbon::parse($dateString . ' ' . $breakStartUtc, 'UTC')
            ->setTimezone($userTimezone)
            ->format('H:i');
        $endLocal = Carbon::parse($dateString . ' ' . $breakEndUtc, 'UTC')
            ->setTimezone($userTimezone)
            ->format('H:i');

        return [
            'startTime' => Carbon::parse($startLocal),
            'endTime' => Carbon::parse($endLocal),
        ];
    }

    /**
     * Calculate the earliest start time and latest end time based on unit settings
     *
     * @param object $unitSettings
     * @return array{startTime: Carbon, endTime: Carbon}
     */
    public function calculateWorkingHours($unitSettings): array
    {
        $days = [
            'sunday' => 'Domingo',
            'monday' => 'Segunda',
            'tuesday' => 'Terça',
            'wednesday' => 'Quarta',
            'thursday' => 'Quinta',
            'friday' => 'Sexta',
            'saturday' => 'Sábado'
        ];

        // Calculate earliest start time (convertendo para timezone do usuário)
        $userTimezone = $unitSettings->timezone ?? 'UTC';
        $referenceDate = now()->format('Y-m-d');
        $earliestStartTime = collect($days)
            ->map(function ($dayName, $dayKey) use ($unitSettings) {
                if (!$unitSettings->$dayKey) {
                    return null;
                }
                return $unitSettings->{$dayKey . '_start'};
            })
            ->filter()
            ->map(function ($time) use ($userTimezone, $referenceDate) {
                $local = Carbon::parse($referenceDate . ' ' . $time, 'UTC')->setTimezone($userTimezone)->format('H:i');
                return Carbon::parse($local);
            })
            ->min();

        // Calculate latest end time (convertendo para timezone do usuário)
        $latestEndTime = collect($days)
            ->map(function ($dayName, $dayKey) use ($unitSettings) {
                if (!$unitSettings->$dayKey) {
                    return null;
                }
                return $unitSettings->{$dayKey . '_end'};
            })
            ->filter()
            ->map(function ($time) use ($userTimezone, $referenceDate) {
                $local = Carbon::parse($referenceDate . ' ' . $time, 'UTC')->setTimezone($userTimezone)->format('H:i');
                return Carbon::parse($local);
            })
            ->max();

        // If no times are configured, use default 8:00 - 18:00
        $startTime = $earliestStartTime ?: Carbon::createFromTime(8, 0, 0);
        $endTime = $latestEndTime ?: Carbon::createFromTime(18, 0, 0);

        return [
            'startTime' => $startTime,
            'endTime' => $endTime
        ];
    }

    /**
     * Get all available time slots for a given day
     *
     * @param Carbon $date
     * @param object $unitSettings
     * @return Collection
     */
    public function getAvailableTimeSlots(Carbon $date, $unitSettings): Collection
    {
        $workingHours = $this->calculateWorkingHours($unitSettings);
        $interval = $unitSettings->appointment_duration_minutes ?? 30;

        $slots = collect();
        $currentTime = $workingHours['startTime']->copy();

        while ($currentTime->lt($workingHours['endTime'])) {
            $slots->push($currentTime->copy());
            $currentTime->addMinutes($interval);
        }

        return $slots;
    }

    /**
     * Check if a time slot is within operating hours for a specific day
     *
     * @param Carbon $time
     * @param string $dayKey
     * @param object $unitSettings
     * @return bool
     */
    public function isWithinOperatingHours(Carbon $time, string $dayKey, $unitSettings): bool
    {
        $isDayEnabled = $unitSettings->$dayKey;
        $dayStartTime = $unitSettings->{$dayKey . '_start'};
        $dayEndTime = $unitSettings->{$dayKey . '_end'};

        // Se o dia não está habilitado, retorna false
        if (!$isDayEnabled) {
            return false;
        }

        // Se não há horários configurados, considera como válido
        if (!$dayStartTime && !$dayEndTime) {
            return true;
        }

        // Comparar apenas o horário (H:i) ignorando a data
        $timeOnly = $time->format('H:i');

        // Converter horários configurados (salvos em UTC) para o fuso do usuário, usando a data do horário analisado
        $userTimezone = $unitSettings->timezone ?? 'UTC';
        $referenceDate = $time->format('Y-m-d');
        $normalizedStart = $dayStartTime
            ? Carbon::parse($referenceDate . ' ' . $dayStartTime, 'UTC')->setTimezone($userTimezone)->format('H:i')
            : null;
        $normalizedEnd = $dayEndTime
            ? Carbon::parse($referenceDate . ' ' . $dayEndTime, 'UTC')->setTimezone($userTimezone)->format('H:i')
            : null;

        // Verificar se está dentro do horário de funcionamento
        // Incluir o horário de início (>=) e excluir o horário de fim (<)
        $isAfterStart = !$normalizedStart || $timeOnly >= $normalizedStart;
        $isBeforeEnd = !$normalizedEnd || $timeOnly < $normalizedEnd;

        return $isAfterStart && $isBeforeEnd;
    }

    /**
     * Get schedule for a specific time slot
     *
     * @param mixed $schedules
     * @param string $currentDate
     * @param string $currentTime
     * @param string $currentEndTime
     * @return mixed
     */
    public function getScheduleForTimeSlot($schedules, string $currentDate, string $currentTime, string $currentEndTime)
    {
        // Convert AnonymousResourceCollection to Collection if needed
        $schedulesCollection = $schedules instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection
            ? collect($schedules->toArray(request()))
            : $schedules;

        return $schedulesCollection->first(function ($schedule) use ($currentDate, $currentTime, $currentEndTime) {
            // Handle both resource and model data
            if (is_array($schedule)) {
                $start = Carbon::parse($schedule['start']);
                $scheduleDate = $start->format('Y-m-d');
                $startTime = $start->format('H:i');
            } else {
                $scheduleDate = $schedule->schedule_date;
                $startTime = $schedule->start_time;
            }

            return $scheduleDate === $currentDate &&
                $startTime >= $currentTime &&
                $startTime < $currentEndTime;
        });
    }

    /**
     * Get customer name from schedule
     *
     * @param mixed $schedule
     * @return string
     */
    public function getCustomerName($schedule): string
    {
        if (is_array($schedule)) {
            return $schedule['customer']['name'] ?? '';
        }
        return $schedule->customer->name ?? '';
    }

    /**
     * Get service type from schedule
     *
     * @param mixed $schedule
     * @return string
     */
    public function getServiceType($schedule): string
    {
        if (is_array($schedule)) {
            return $schedule['unit_service_type']['name'] ?? '';
        }
        return $schedule->unitServiceType->name ?? '';
    }

    /**
     * Get status from schedule
     *
     * @param mixed $schedule
     * @return string
     */
    public function getStatus($schedule): string
    {
        if (is_array($schedule)) {
            return $schedule['status'] ?? '';
        }
        return $schedule->status ?? '';
    }
}
