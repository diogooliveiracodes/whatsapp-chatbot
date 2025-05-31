<?php

namespace App\Repositories\Interfaces;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;

interface ScheduleRepositoryInterface
{
    public function findById(int $id): ?Schedule;
    public function findByUnitId(int $unitId): Collection;
    public function create(array $data): Schedule;
    public function update(Schedule $schedule, array $data): bool;
    public function delete(Schedule $schedule): bool;
    public function findConflictingSchedule(int $unitId, string $scheduleDate, string $startTime, string $endTime, ?int $currentScheduleId = null): ?Schedule;
}
