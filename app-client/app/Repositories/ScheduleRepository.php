<?php

namespace App\Repositories;

use App\Models\Schedule;
use App\Repositories\Interfaces\ScheduleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use App\Services\Unit\UnitService;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function __construct(protected Schedule $model, protected UnitService $unitService) {}

    public function findById(int $id): ?Schedule
    {
        return $this->model->find($id);
    }

    public function findByUnitIdAndDate(int $unitId, Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->model
            ->with(['customer', 'user', 'unitServiceType'])
            ->where('unit_id', $unitId)
            ->whereBetween('schedule_date', [$startDate, $endDate])
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
        return $this->model
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
}
