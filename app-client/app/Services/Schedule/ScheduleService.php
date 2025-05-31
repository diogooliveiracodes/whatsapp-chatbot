<?php
namespace App\Services\Schedule;

use App\Repositories\Interfaces\ScheduleRepositoryInterface;
use App\Services\Schedule\Interfaces\WorkingDaysValidatorInterface;
use App\Services\Schedule\Interfaces\WorkingHoursValidatorInterface;
use App\Services\Schedule\Interfaces\ScheduleConflictValidatorInterface;

class ScheduleService
{
    public function __construct(
        private WorkingDaysValidatorInterface $workingDaysValidator,
        private WorkingHoursValidatorInterface $workingHoursValidator,
        private ScheduleConflictValidatorInterface $scheduleConflictValidator,
        private ScheduleRepositoryInterface $scheduleRepository
    ) {
    }

    public function isOutsideWorkingDays($date, $companySettings): bool
    {
        return $this->workingDaysValidator->isOutsideWorkingDays($date, $companySettings);
    }

    public function isOutsideWorkingHours($startTime, $endTime, $companySettings): bool
    {
        return $this->workingHoursValidator->isOutsideWorkingHours($startTime, $endTime, $companySettings);
    }

    public function hasConflict($unitId, $scheduleDate, $startTime, $endTime, $currentScheduleId): bool
    {
        return $this->scheduleRepository->findConflictingSchedule($unitId, $scheduleDate, $startTime, $endTime, $currentScheduleId) !== null;
    }

    public function getSchedulesByUnit(int $unitId)
    {
        return $this->scheduleRepository->findByUnitId($unitId);
    }

    public function createSchedule(array $data)
    {
        return $this->scheduleRepository->create($data);
    }

    public function updateSchedule($schedule, array $data)
    {
        return $this->scheduleRepository->update($schedule, $data);
    }

    public function deleteSchedule($schedule)
    {
        return $this->scheduleRepository->delete($schedule);
    }

    public function cancelSchedule($schedule)
    {
        $schedule->status = 'cancelled';
        $schedule->save();
        return $this->scheduleRepository->delete($schedule);
    }
}
