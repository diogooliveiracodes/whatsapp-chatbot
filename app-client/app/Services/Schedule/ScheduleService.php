<?php
namespace App\Services\Schedule;

use App\Repositories\Interfaces\ScheduleRepositoryInterface;
use App\Services\Schedule\Interfaces\WorkingDaysValidatorInterface;
use App\Services\Schedule\Interfaces\WorkingHoursValidatorInterface;
use App\Services\Schedule\Interfaces\ScheduleConflictValidatorInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Exceptions\Schedule\OutsideWorkingDaysException;
use App\Exceptions\Schedule\OutsideWorkingHoursException;
use App\Exceptions\Schedule\ScheduleConflictException;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;

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
     * @param ScheduleRepositoryInterface $scheduleRepository
     * @param ScheduleTimeService $scheduleTimeService
     */
    public function __construct(
        private WorkingDaysValidatorInterface $workingDaysValidator,
        private WorkingHoursValidatorInterface $workingHoursValidator,
        private ScheduleConflictValidatorInterface $scheduleConflictValidator,
        private ScheduleRepositoryInterface $scheduleRepository,
        private ScheduleTimeService $scheduleTimeService
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
    public function isOutsideWorkingHours($startTime, $endTime, $unitSettings): bool
    {
        // Convert Carbon objects to strings if needed
        if ($startTime instanceof Carbon) {
            $startTime = $startTime->format('H:i');
        }
        if ($endTime instanceof Carbon) {
            $endTime = $endTime->format('H:i');
        }

        return $this->workingHoursValidator->isOutsideWorkingHours($startTime, $endTime, $unitSettings);
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
     */
    public function validateAndCreateSchedule(array $validated, object $unit, object $unitSettings, int $duration)
    {
        $scheduleDate = Carbon::parse($validated['schedule_date']);
        $validated['end_time'] = Carbon::parse($validated['start_time'])
            ->addMinutes($duration)
            ->format('H:i');

        if ($this->isOutsideWorkingDays($scheduleDate, $unitSettings)) {
            throw new OutsideWorkingDaysException();
        }

        if ($this->isOutsideWorkingHours($validated['start_time'], $validated['end_time'], $unitSettings)) {
            throw new OutsideWorkingHoursException();
        }

        if ($this->hasConflict($unit->id, $validated['schedule_date'], $validated['start_time'], $validated['end_time'], null)) {
            throw new ScheduleConflictException();
        }

        $scheduleData = array_merge($validated, [
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

    public function handleScheduleUpdate(array $validated, Schedule $schedule)
    {
        $unit = Auth::user()->unit;

        $this->validateAndUpdateSchedule($validated, $schedule);

    }

    public function validateAndUpdateSchedule(array $validated, Schedule $schedule)
    {
        $scheduleDate = Carbon::parse($validated['schedule_date']);
        $validated['end_time'] = Carbon::parse($validated['start_time'])
            ->addMinutes($schedule->unit->unitSettings->appointment_duration_minutes)
            ->format('H:i');

        if ($this->isOutsideWorkingDays($scheduleDate, $schedule->unit->unitSettings)) {
            throw new OutsideWorkingDaysException();
        }

        if ($this->isOutsideWorkingHours($validated['start_time'], $validated['end_time'], $schedule->unit->unitSettings)) {
            throw new OutsideWorkingHoursException();
        }

        if($validated['schedule_date'] != $schedule->schedule_date->format('Y-m-d') || $validated['start_time'] != $schedule->start_time) {
            if ($this->hasConflict($schedule->unit->id, $validated['schedule_date'], $validated['start_time'], $validated['end_time'], null)) {
                throw new ScheduleConflictException();
            }
        }

        $scheduleData = array_merge($validated, [
            'unit_id' => $schedule->unit->id,
            'user_id' => Auth::id(),
            'is_confirmed' => true,
        ]);

        return $this->scheduleRepository->update($schedule, $scheduleData);
    }
}
