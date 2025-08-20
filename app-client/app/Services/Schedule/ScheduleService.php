<?php
namespace App\Services\Schedule;

use App\Services\Schedule\Interfaces\WorkingDaysValidatorInterface;
use App\Services\Schedule\Interfaces\WorkingHoursValidatorInterface;
use App\Services\Schedule\Interfaces\ScheduleConflictValidatorInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Exceptions\Schedule\OutsideWorkingDaysException;
use App\Exceptions\Schedule\OutsideWorkingHoursException;
use App\Exceptions\Schedule\ScheduleConflictException;
use App\Exceptions\Schedule\ScheduleBlockedException;
use App\Exceptions\Schedule\PastScheduleException;
use App\Exceptions\Schedule\InsideBreakPeriodException;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Repositories\ScheduleRepository;
use App\Services\Schedule\ScheduleBlockService;

/**
 * Service class for handling schedule-related operations
 */
class ScheduleService
{
    /**
     * Create a new ScheduleService instance
     *
     * @param WorkingDaysValidatorInterface $workingDaysValidator
     * @param WorkingHoursValidatorInterface $workingHoursValidator
     * @param ScheduleConflictValidatorInterface $scheduleConflictValidator
     * @param ScheduleRepository $scheduleRepository
     * @param ScheduleTimeService $scheduleTimeService
     */
    public function __construct(
        private WorkingDaysValidatorInterface $workingDaysValidator,
        private WorkingHoursValidatorInterface $workingHoursValidator,
        private ScheduleConflictValidatorInterface $scheduleConflictValidator,
        private ScheduleRepository $scheduleRepository,
        private ScheduleTimeService $scheduleTimeService,
        private ScheduleBlockService $scheduleBlockService
    ) {
    }

    /**
     * Check if a date is outside working days
     *
     * @param mixed $date
     * @param object $unitSettings
     * @return bool
     */
    public function isOutsideWorkingDays($date, $unitSettings): bool
    {
        return $this->workingDaysValidator->isOutsideWorkingDays($date, $unitSettings);
    }

    /**
     * Check if time range is outside working hours
     *
     * @param mixed $startTime
     * @param mixed $endTime
     * @param object $unitSettings
     * @return bool
     */
    public function isOutsideWorkingHours($scheduleDate, $startTime, $endTime, $unitSettings): bool
    {
        // Convert Carbon objects to strings if needed
        if ($startTime instanceof Carbon) {
            $startTime = $startTime->format('H:i');
        }
        if ($endTime instanceof Carbon) {
            $endTime = $endTime->format('H:i');
        }

        return $this->workingHoursValidator->isOutsideWorkingHours($scheduleDate, $startTime, $endTime, $unitSettings);
    }

    /**
     * Check if there is a schedule conflict for the given parameters
     *
     * @param int $unitId
     * @param mixed $scheduleDate
     * @param mixed $startTime
     * @param mixed $endTime
     * @param mixed $currentScheduleId
     * @return bool
     */
    public function hasConflict($unitId, $scheduleDate, $startTime, $endTime, $currentScheduleId): bool
    {
        return $this->scheduleRepository->findConflictingSchedule($unitId, $scheduleDate, $startTime, $endTime, $currentScheduleId) !== null;
    }

    /**
     * Get all schedules for a specific unit
     *
     * @param int $unitId
     * @return mixed
     */
    public function getSchedulesByUnitAndDate(int $unitId, $date)
    {
        [$startDate, $endDate] = $this->getStartAndEndDate($date);
        return $this->scheduleRepository->findByUnitIdAndDate($unitId, $startDate, $endDate);
    }

    /**
     * Get all schedules for a specific unit and day
     *
     * @param int $unitId
     * @param mixed $date
     * @return mixed
     */
    public function getSchedulesByUnitAndDay(int $unitId, $date)
    {
        $date = Carbon::parse($date);
        return $this->scheduleRepository->findByUnitIdAndDay($unitId, $date);
    }

    /**
     * Get the start and end date for the week
     *
     * @param string $date
     * @return array
     */
    public function getStartAndEndDate($date = null)
    {
        $startDate = Carbon::parse($date ?? now())->startOfWeek();
        $endDate = $startDate->clone()->addDays(6);

        return [$startDate, $endDate];
    }

    /**
     * Create a new schedule
     *
     * @param array $data
     * @return mixed
     */
    public function createSchedule(array $data)
    {
        return $this->scheduleRepository->create($data);
    }

    /**
     * Update an existing schedule
     *
     * @param mixed $schedule
     * @param array $data
     * @return mixed
     */
    public function updateSchedule($schedule, array $data)
    {
        return $this->scheduleRepository->update($schedule, $data);
    }

    /**
     * Delete a schedule
     *
     * @param mixed $schedule
     * @return mixed
     * @throws PastScheduleException
     */
    public function deleteSchedule($schedule)
    {
        // Verificar se o agendamento já passou
        $scheduleEndDateTime = Carbon::parse($schedule->schedule_date->format('Y-m-d') . ' ' . $schedule->end_time);

        // Obter o fuso horário da unidade
        $userTimezone = $schedule->unit->unitSettings->timezone ?? 'UTC';

        // Converter para o fuso horário da unidade
        $currentTimeInUserTimezone = now()->setTimezone($userTimezone);

        // Um agendamento só "passou" quando o horário de término já foi ultrapassado
        if ($currentTimeInUserTimezone->gt($scheduleEndDateTime)) {
            throw new \App\Exceptions\Schedule\PastScheduleException();
        }

        return $this->scheduleRepository->delete($schedule);
    }

    /**
     * Get working hours for a unit
     *
     * @param object $unitSettings
     * @return array{startTime: Carbon, endTime: Carbon}
     */
    public function getWorkingHours($unitSettings): array
    {
        return $this->scheduleTimeService->calculateWorkingHours($unitSettings);
    }

    /**
     * Get working hours for a specific day
     *
     * @param string $dayKey
     * @param object $unitSettings
     * @return array{startTime: Carbon, endTime: Carbon}
     */
    public function calculateWorkingHoursForDay(string $dayKey, $unitSettings): array
    {
        return $this->scheduleTimeService->calculateWorkingHoursForDay($dayKey, $unitSettings);
    }

    /**
     * Get available time slots for a given date
     *
     * @param Carbon $date
     * @param object $unitSettings
     * @return \Illuminate\Support\Collection
     */
    public function getAvailableTimeSlots(Carbon $date, $unitSettings)
    {
        return $this->scheduleTimeService->getAvailableTimeSlots($date, $unitSettings);
    }

    /**
     * Get break period for a specific day
     *
     * @param string $dayKey
     * @param Carbon $referenceDate
     * @param object $unitSettings
     * @return array{startTime: Carbon|null, endTime: Carbon|null}
     */
    public function getBreakForDay(string $dayKey, Carbon $referenceDate, $unitSettings): array
    {
        return $this->scheduleTimeService->calculateBreakForDay($dayKey, $unitSettings, $referenceDate);
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
        return $this->scheduleTimeService->isWithinOperatingHours($time, $dayKey, $unitSettings);
    }

    /**
     * Get schedule for a specific time slot
     *
     * @param Collection|AnonymousResourceCollection $schedules
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

        return $this->scheduleTimeService->getScheduleForTimeSlot($schedulesCollection, $currentDate, $currentTime, $currentEndTime);
    }

    /**
     * Get customer name from schedule
     *
     * @param mixed $schedule
     * @return string
     */
    public function getCustomerName($schedule): string
    {
        return $this->scheduleTimeService->getCustomerName($schedule);
    }

    /**
     * Get service type from schedule
     *
     * @param mixed $schedule
     * @return string
     */
    public function getServiceType($schedule): string
    {
        return $this->scheduleTimeService->getServiceType($schedule);
    }

    /**
     * Get status from schedule
     *
     * @param mixed $schedule
     * @return string
     */
    public function getStatus($schedule): string
    {
        return $this->scheduleTimeService->getStatus($schedule);
    }

    /**
     * Convert date and time from user timezone to UTC
     *
     * @param array $validated
     * @param object $unitSettings
     * @return array
     */
    private function convertToUtc(array $validated, object $unitSettings): array
    {
        // Get the timezone from unit settings or use default
        $userTimezone = $unitSettings->timezone ?? 'America/Sao_Paulo';

        // Convert schedule_date to UTC
        $scheduleDate = Carbon::parse($validated['schedule_date'], $userTimezone)->setTimezone('UTC');
        $validated['schedule_date'] = $scheduleDate->format('Y-m-d');

        // For start_time, we need to create a full datetime and then convert to UTC
        $dateTimeString = $validated['schedule_date'] . ' ' . $validated['start_time'] . ':00';
        $startDateTime = Carbon::parse($dateTimeString, $userTimezone)->setTimezone('UTC');
        $validated['start_time'] = $startDateTime->format('H:i');

        return $validated;
    }

    /**
     * Convert UTC date and time to user timezone for display
     *
     * @param string $date
     * @param string $time
     * @param object $unitSettings
     * @return array
     */
    private function convertFromUtc(string $date, string $time, object $unitSettings): array
    {
        // Get the timezone from unit settings or use default
        $userTimezone = $unitSettings->timezone ?? 'America/Sao_Paulo';

        // Create datetime in UTC and convert to user timezone
        $utcDateTime = Carbon::parse($date)->setTimeFromTimeString($time);
        $userDateTime = $utcDateTime->copy()->setTimezone($userTimezone);

        return [
            'date' => $userDateTime->format('Y-m-d'),
            'time' => $userDateTime->format('H:i')
        ];
    }

    /**
     * Validate and create a new schedule
     *
     * @param array $validated
     * @param object $unit
     * @param object $unitSettings
     * @param int $duration
     * @return mixed
     * @throws OutsideWorkingDaysException
     * @throws OutsideWorkingHoursException
     * @throws ScheduleConflictException
     * @throws ScheduleBlockedException
     */
    public function validateAndCreateSchedule(array $validated, object $unit, object $unitSettings, int $duration)
    {
        // Validate with original data (user timezone) before converting to UTC
        $scheduleDate = Carbon::parse($validated['schedule_date']);
        $endTime = Carbon::parse($validated['start_time'])->addMinutes($duration)->format('H:i');

        if ($this->isOutsideWorkingDays($scheduleDate, $unitSettings)) {
            throw new OutsideWorkingDaysException();
        }

        if ($this->isOutsideWorkingHours($scheduleDate, $validated['start_time'], $endTime, $unitSettings)) {
            throw new OutsideWorkingHoursException();
        }

        if ($this->isInsideBreakPeriod($scheduleDate, $validated['start_time'], $endTime, $unitSettings)) {
            throw new InsideBreakPeriodException();
        }

        // Convert to UTC after validation
        $utcData = $this->convertToUtc($validated, $unitSettings);
        $utcData['end_time'] = Carbon::parse($utcData['start_time'])->addMinutes($duration)->format('H:i');

        if ($this->hasConflict($unit->id, $utcData['schedule_date'], $utcData['start_time'], $utcData['end_time'], null)) {
            throw new ScheduleConflictException();
        }

        if ($this->scheduleBlockService->isTimeSlotBlocked($unit->id, $utcData['schedule_date'], $utcData['start_time'], $utcData['end_time'])) {
            throw new ScheduleBlockedException();
        }

        $scheduleData = array_merge($utcData, [
            'unit_id' => $unit->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'is_confirmed' => true,
        ]);

        return $this->createSchedule($scheduleData);
    }

    /**
     * Handle schedule creation with proper exception handling
     *
     * @param array $validated
     * @param object|null $unit Unit to use for schedule creation (optional)
     * @return Schedule The created schedule
     * @throws OutsideWorkingDaysException
     * @throws OutsideWorkingHoursException
     * @throws ScheduleConflictException
     * @throws ScheduleBlockedException
     */
    public function handleScheduleCreation(array $validated, $unit = null): Schedule
    {
        if (!$unit) {
            $unit = Auth::user()->unit;
        }
        $unitSettings = $unit->unitSettings;

        return $this->validateAndCreateSchedule($validated, $unit, $unitSettings, $unitSettings->appointment_duration_minutes);
    }

    /**
     * Validate and update an existing schedule
     *
     * @param array $validated
     * @param Schedule $schedule
     * @param object|null $selectedUnit Unit to use for validation (optional)
     * @return Schedule The updated schedule
     * @throws OutsideWorkingDaysException
     * @throws OutsideWorkingHoursException
     * @throws ScheduleConflictException
     * @throws ScheduleBlockedException
     */
    public function validateAndUpdateSchedule(array $validated, Schedule $schedule, $selectedUnit = null): Schedule
    {
        // Use selected unit if provided, otherwise use schedule's unit
        $unit = $selectedUnit ?? $schedule->unit;
        $unitSettings = $unit->unitSettings;

        // Validate with original data (user timezone) before converting to UTC
        $scheduleDate = Carbon::parse($validated['schedule_date']);
        $endTime = Carbon::parse($validated['start_time'])->addMinutes($unitSettings->appointment_duration_minutes)->format('H:i');

        if ($this->isOutsideWorkingDays($scheduleDate, $unitSettings)) {
            throw new OutsideWorkingDaysException();
        }

        if ($this->isOutsideWorkingHours($scheduleDate, $validated['start_time'], $endTime, $unitSettings)) {
            throw new OutsideWorkingHoursException();
        }

        if ($this->isInsideBreakPeriod($scheduleDate, $validated['start_time'], $endTime, $unitSettings)) {
            throw new InsideBreakPeriodException();
        }

        // Convert to UTC after validation
        $utcData = $this->convertToUtc($validated, $unitSettings);
        $utcData['end_time'] = Carbon::parse($utcData['start_time'])->addMinutes($unitSettings->appointment_duration_minutes)->format('H:i');

        // Only check for conflicts if the schedule date or time has changed
        $originalDate = $schedule->schedule_date->format('Y-m-d');
        $originalStartTime = Carbon::parse($schedule->start_time)->format('H:i');

        if($utcData['schedule_date'] != $originalDate || $utcData['start_time'] != $originalStartTime) {
            // Exclude the current schedule from conflict check
            if ($this->hasConflict($unit->id, $utcData['schedule_date'], $utcData['start_time'], $utcData['end_time'], $schedule->id)) {
                throw new ScheduleConflictException();
            }
        }

        // Check if the time slot is blocked (only if date or time changed)
        if($utcData['schedule_date'] != $originalDate || $utcData['start_time'] != $originalStartTime) {
            if ($this->scheduleBlockService->isTimeSlotBlocked($unit->id, $utcData['schedule_date'], $utcData['start_time'], $utcData['end_time'])) {
                throw new ScheduleBlockedException();
            }
        }

        $scheduleData = array_merge($utcData, [
            'unit_id' => $unit->id,
            'user_id' => Auth::id(),
            'is_confirmed' => true,
        ]);

        return $this->scheduleRepository->update($schedule, $scheduleData);
    }

    /**
     * Check if a time range intersects with the configured break period for the given day
     */
    private function isInsideBreakPeriod(Carbon $scheduleDate, string $startTime, string $endTime, $unitSettings): bool
    {
        // Map day of week to model keys (1 => sunday, 7 => saturday)
        $dayOfWeek = $scheduleDate->dayOfWeek + 1;
        $dayMap = [
            1 => 'sunday',
            2 => 'monday',
            3 => 'tuesday',
            4 => 'wednesday',
            5 => 'thursday',
            6 => 'friday',
            7 => 'saturday',
        ];
        $dayKey = $dayMap[$dayOfWeek] ?? 'monday';

        // If day not enabled, handled by other validation
        if (!$unitSettings->$dayKey) {
            return false;
        }

        $hasBreak = (bool) ($unitSettings->{$dayKey . '_has_break'} ?? false);
        $breakStartUtc = $unitSettings->{$dayKey . '_break_start'} ?? null;
        $breakEndUtc = $unitSettings->{$dayKey . '_break_end'} ?? null;

        if (!$hasBreak || !$breakStartUtc || !$breakEndUtc) {
            return false;
        }

        // Convert break times (stored in UTC) to user timezone using the schedule date as reference
        $userTimezone = $unitSettings->timezone ?? 'America/Sao_Paulo';
        $referenceDate = $scheduleDate->format('Y-m-d');

        $breakStartLocal = \App\Helpers\TimezoneHelper::convertTimeFromUtc($breakStartUtc, $userTimezone, $referenceDate);
        $breakEndLocal = \App\Helpers\TimezoneHelper::convertTimeFromUtc($breakEndUtc, $userTimezone, $referenceDate);

        if (!$breakStartLocal || !$breakEndLocal) {
            return false;
        }

        // Build comparable Carbon instances in the same timezone
        $startTimeCarbon = Carbon::parse($referenceDate . ' ' . $startTime, $userTimezone);
        $endTimeCarbon = Carbon::parse($referenceDate . ' ' . $endTime, $userTimezone);
        $breakStartCarbon = Carbon::parse($referenceDate . ' ' . $breakStartLocal, $userTimezone);
        $breakEndCarbon = Carbon::parse($referenceDate . ' ' . $breakEndLocal, $userTimezone);

        // Intersects if start < breakEnd and end > breakStart
        return $startTimeCarbon->lt($breakEndCarbon) && $endTimeCarbon->gt($breakStartCarbon);
    }

    public function getActiveSchedulesFromNow(int $unitId): bool
    {
        return $this->scheduleRepository->getActiveSchedulesFromNow($unitId);
    }
}
