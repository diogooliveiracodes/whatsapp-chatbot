<?php

namespace App\Repositories\Interfaces;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

interface ScheduleRepositoryInterface
{
    public function findById(int $id): ?Schedule;
    public function findByUnitIdAndDate(int $unitId, Carbon $startDate, Carbon $endDate): Collection;
    public function create(array $data): Schedule;
    public function update(Schedule $schedule, array $data): bool;
    public function delete(Schedule $schedule): bool;
    public function findConflictingSchedule(int $unitId, string $scheduleDate, string $startTime, string $endTime, ?int $currentScheduleId = null): ?Schedule;
}
