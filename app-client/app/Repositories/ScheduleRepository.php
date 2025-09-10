<?php

namespace App\Repositories;

use App\Models\Schedule;
use App\Services\Unit\UnitService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

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

    public function findByUuid(string $uuid): ?Schedule
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    public function findByUnitIdAndDate(int $unitId, Carbon $startDate, Carbon $endDate): Collection
    {
        return $this
            ->model
            ->with(['customer', 'user', 'unitServiceType', 'unit'])
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
            ->with(['customer', 'user', 'unitServiceType', 'unit'])
            ->where('unit_id', $unitId)
            ->where('schedule_date', $date->format('Y-m-d'))
            ->orderBy('start_time')
            ->get();
    }

    public function create(array $data): Schedule
    {
        $data['uuid'] = Str::uuid();
        $schedule = $this->model->create($data);
        return $schedule->load(['customer', 'user', 'unitServiceType', 'unit']);
    }

    public function update(Schedule $schedule, array $data): Schedule
    {
        $schedule->update($data);
        return $schedule->fresh(['customer', 'user', 'unitServiceType', 'unit']);
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
     * Paginate schedules for a given customer UUID ordered from newest to oldest
     */
    public function paginateByCustomerUuid(string $customerUuid, int $perPage = 10): LengthAwarePaginator
    {
        return $this
            ->model
            ->with(['customer', 'user', 'unitServiceType', 'unit.unitSettings'])
            ->whereHas('customer', function ($q) use ($customerUuid) {
                $q->where('uuid', $customerUuid);
            })
            ->orderByDesc('schedule_date')
            ->orderByDesc('start_time')
            ->paginate($perPage);
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
