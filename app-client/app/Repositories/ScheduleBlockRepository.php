<?php

namespace App\Repositories;

use App\Models\ScheduleBlock;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ScheduleBlockRepository
{
    public function __construct(
        protected ScheduleBlock $model
    ) {}

    public function findById(int $id): ?ScheduleBlock
    {
        return $this->model->find($id);
    }

    public function findByUnitIdAndDate(int $unitId, Carbon $startDate, Carbon $endDate): Collection
    {
        return $this
            ->model
            ->with(['user', 'company'])
            ->where('unit_id', $unitId)
            ->where('active', true)
            ->whereBetween('block_date', [$startDate, $endDate])
            ->get();
    }

    public function findByCompanyIdAndDate(int $companyId, Carbon $startDate, Carbon $endDate): Collection
    {
        return $this
            ->model
            ->with(['user', 'company', 'unit'])
            ->where('company_id', $companyId)
            ->where('active', true)
            ->whereBetween('block_date', [$startDate, $endDate])
            ->get();
    }

    public function findConflictingBlocks(int $unitId, string $blockDate, string $startTime, string $endTime, ?int $currentBlockId = null): Collection
    {
        return $this
            ->model
            ->where('unit_id', $unitId)
            ->where('active', true)
            ->when($currentBlockId, function ($query) use ($currentBlockId) {
                return $query->where('id', '!=', $currentBlockId);
            })
            ->where('block_date', $blockDate)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('block_type', 'full_day')
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('block_type', 'time_slot')
                            ->where(function ($subQuery) use ($startTime, $endTime) {
                                $subQuery->where(function ($sq) use ($startTime) {
                                    $sq->where('start_time', '<=', $startTime)
                                        ->where('end_time', '>', $startTime);
                                })->orWhere(function ($sq) use ($endTime) {
                                    $sq->where('start_time', '<', $endTime)
                                        ->where('end_time', '>=', $endTime);
                                });
                            });
                    });
            })
            ->get();
    }

    public function create(array $data): ScheduleBlock
    {
        return $this->model->create($data);
    }

    public function update(ScheduleBlock $scheduleBlock, array $data): bool
    {
        return $scheduleBlock->update($data);
    }

    public function delete(ScheduleBlock $scheduleBlock): bool
    {
        return $scheduleBlock->delete();
    }

            public function getActiveBlocksByUnit(int $unitId): Collection
    {
        // Filtra bloqueios ativos para hoje e datas futuras
        return $this
            ->model
            ->with(['user', 'company', 'unit'])
            ->where('unit_id', $unitId)
            ->where('active', true)
            ->where('block_date', '>=', now()->startOfDay())
            ->orderBy('block_date')
            ->orderBy('start_time')
            ->get();
    }

    public function getActiveBlocksByCompany(int $companyId): Collection
    {
        return $this
            ->model
            ->with(['user', 'company', 'unit'])
            ->where('company_id', $companyId)
            ->where('active', true)
            ->where('block_date', '>=', now()->startOfDay())
            ->orderBy('block_date')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Deactivate blocks by company ID
     *
     * @param int $companyId
     * @return void
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->where('company_id', $companyId)->update(['active' => false]);
    }
}
