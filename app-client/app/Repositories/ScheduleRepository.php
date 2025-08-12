<?php

namespace App\Repositories;

use App\Models\Schedule;
use App\Services\Unit\UnitService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ScheduleRepository
{
    public function __construct(
        protected Schedule $model,
        protected UnitService $unitService
    ) {}

    public function findById(int $id): ?Schedule
    {
        return $this->model->find($id);
    }

    public function findByUnitIdAndDate(int $unitId, Carbon $startDate, Carbon $endDate): Collection
    {
        return $this
            ->model
            ->with(['customer', 'user', 'unitServiceType'])
            ->where('unit_id', $unitId)
            ->whereBetween('schedule_date', [$startDate, $endDate])
            ->get();
    }

    /**
     * Find schedules for a specific unit and date
     *
     * @param int $unitId
     * @param Carbon $date
     * @return Collection
     */
    public function findByUnitIdAndDay(int $unitId, Carbon $date): Collection
    {
        return $this
            ->model
            ->with(['customer', 'user', 'unitServiceType'])
            ->where('unit_id', $unitId)
            ->where('schedule_date', $date->format('Y-m-d'))
            ->orderBy('start_time')
            ->get();
    }

    public function create(array $data): Schedule
    {
        return $this->model->create($data);
    }

    public function update(Schedule $schedule, array $data): bool
    {
        return $schedule->update($data);
    }

    public function delete(Schedule $schedule): bool
    {
        return $schedule->delete();
    }

    public function findConflictingSchedule(int $unitId, string $scheduleDate, string $startTime, string $endTime, ?int $currentScheduleId = null): ?Schedule
    {
        return $this
            ->model
            ->where('unit_id', $unitId)
            ->when($currentScheduleId, function ($query) use ($currentScheduleId) {
                return $query->where('id', '!=', $currentScheduleId);
            })
            ->where('schedule_date', $scheduleDate)
            ->where(function ($query) use ($startTime, $endTime) {
                $query
                    ->where(function ($q) use ($startTime) {
                        $q->where('start_time', '<=', $startTime)->where('end_time', '>', $startTime);
                    })
                    ->orWhere(function ($q) use ($endTime) {
                        $q->where('start_time', '<', $endTime)->where('end_time', '>=', $endTime);
                    });
            })
            ->first();
    }

    public function getActiveSchedulesFromNow(int $unitId): bool
    {
        return $this
            ->model
            ->where('unit_id', $unitId)
            ->whereNot('status', 'cancelled')
            ->where('schedule_date', '>=', now())
            ->exists();
    }

    /**
     * Deactivate schedules by company ID
     *
     * @param int $companyId
     * @return void
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->whereHas('unit', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->update(['status' => 'inactive']);
    }
}
