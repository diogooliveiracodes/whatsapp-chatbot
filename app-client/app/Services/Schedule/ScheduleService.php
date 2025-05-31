<?php
namespace App\Services\Schedule;

use App\Services\Schedule\Interfaces\WorkingDaysValidatorInterface;
use App\Services\Schedule\Interfaces\WorkingHoursValidatorInterface;
use App\Services\Schedule\Interfaces\ScheduleConflictValidatorInterface;

class ScheduleService
{
    private WorkingDaysValidatorInterface $workingDaysValidator;
    private WorkingHoursValidatorInterface $workingHoursValidator;
    private ScheduleConflictValidatorInterface $scheduleConflictValidator;

    public function __construct(
        WorkingDaysValidatorInterface $workingDaysValidator,
        WorkingHoursValidatorInterface $workingHoursValidator,
        ScheduleConflictValidatorInterface $scheduleConflictValidator
    ) {
        $this->workingDaysValidator = $workingDaysValidator;
        $this->workingHoursValidator = $workingHoursValidator;
        $this->scheduleConflictValidator = $scheduleConflictValidator;
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
        return $this->scheduleConflictValidator->hasConflict($unitId, $scheduleDate, $startTime, $endTime, $currentScheduleId);
    }
}
