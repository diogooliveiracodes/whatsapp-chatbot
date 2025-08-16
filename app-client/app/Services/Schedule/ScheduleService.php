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
     */
    public function deleteSchedule($schedule)
    {
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
        // Convert to UTC before validation
        $utcData = $this->convertToUtc($validated, $unitSettings);

        $scheduleDate = Carbon::parse($utcData['schedule_date']);
        $utcData['end_time'] = Carbon::parse($utcData['start_time'])
            ->addMinutes($duration)
            ->format('H:i');

        if ($this->isOutsideWorkingDays($scheduleDate, $unitSettings)) {
            throw new OutsideWorkingDaysException();
        }

        if ($this->isOutsideWorkingHours($scheduleDate, $utcData['start_time'], $utcData['end_time'], $unitSettings)) {
            throw new OutsideWorkingHoursException();
        }

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
     * @return array{success: bool, message: string, redirect: string}
     * @throws OutsideWorkingDaysException
     * @throws OutsideWorkingHoursException
     * @throws ScheduleConflictException
     * @throws ScheduleBlockedException
     */
    public function handleScheduleCreation(array $validated): array
    {
        $unit = Auth::user()->unit;
        $unitSettings = $unit->unitSettings;

        $this->validateAndCreateSchedule($validated, $unit, $unitSettings, $unitSettings->appointment_duration_minutes);

        return [
            'success' => true,
            'message' => __('schedules.messages.created'),
            'redirect' => 'schedules.index'
        ];
    }

    /**
     * Validate and update an existing schedule
     *
     * @param array $validated
     * @param Schedule $schedule
     * @return mixed
     * @throws OutsideWorkingDaysException
     * @throws OutsideWorkingHoursException
     * @throws ScheduleConflictException
     * @throws ScheduleBlockedException
     */
    public function validateAndUpdateSchedule(array $validated, Schedule $schedule)
    {
        // Convert to UTC before validation
        $utcData = $this->convertToUtc($validated, $schedule->unit->unitSettings);

        $scheduleDate = Carbon::parse($utcData['schedule_date']);
        $utcData['end_time'] = Carbon::parse($utcData['start_time'])
            ->addMinutes($schedule->unit->unitSettings->appointment_duration_minutes)
            ->format('H:i');

        if ($this->isOutsideWorkingDays($scheduleDate, $schedule->unit->unitSettings)) {
            throw new OutsideWorkingDaysException();
        }

        if ($this->isOutsideWorkingHours($scheduleDate, $utcData['start_time'], $utcData['end_time'], $schedule->unit->unitSettings)) {
            throw new OutsideWorkingHoursException();
        }

        if($utcData['schedule_date'] != $schedule->schedule_date->format('Y-m-d') || $utcData['start_time'] != Carbon::parse($schedule->start_time)->format('H:i')) {
            if ($this->hasConflict($schedule->unit->id, $utcData['schedule_date'], $utcData['start_time'], $utcData['end_time'], null)) {
                throw new ScheduleConflictException();
            }
        }

        if ($this->scheduleBlockService->isTimeSlotBlocked($schedule->unit->id, $utcData['schedule_date'], $utcData['start_time'], $utcData['end_time'])) {
            throw new ScheduleBlockedException();
        }

        $scheduleData = array_merge($utcData, [
            'unit_id' => $schedule->unit->id,
            'user_id' => Auth::id(),
            'is_confirmed' => true,
        ]);

        return $this->scheduleRepository->update($schedule, $scheduleData);
    }

    public function getActiveSchedulesFromNow(int $unitId): bool
    {
        return $this->scheduleRepository->getActiveSchedulesFromNow($unitId);
    }
}
