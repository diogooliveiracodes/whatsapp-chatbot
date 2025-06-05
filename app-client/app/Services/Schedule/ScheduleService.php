<?php
namespace App\Services\Schedule;

use App\Repositories\Interfaces\ScheduleRepositoryInterface;
use App\Services\Schedule\Interfaces\WorkingDaysValidatorInterface;
use App\Services\Schedule\Interfaces\WorkingHoursValidatorInterface;
use App\Services\Schedule\Interfaces\ScheduleConflictValidatorInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
    public function getSchedulesByUnit(int $unitId)
    {
        return $this->scheduleRepository->findByUnitId($unitId);
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
     * Cancel a schedule and delete it
     *
     * @param mixed $schedule
     * @return mixed
     */
    public function cancelSchedule($schedule)
    {
        $schedule->status = 'cancelled';
        $schedule->save();
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
}
