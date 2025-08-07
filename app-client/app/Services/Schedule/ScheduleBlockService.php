<?php

namespace App\Services\Schedule;

use App\Enum\ScheduleBlockTypeEnum;
use App\Models\ScheduleBlock;
use App\Repositories\ScheduleBlockRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ScheduleBlockService
{
    public function __construct(
        private ScheduleBlockRepository $scheduleBlockRepository
    ) {}

    /**
     * Get all blocks for a specific unit and date range
     */
    public function getBlocksByUnitAndDate(int $unitId, Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->scheduleBlockRepository->findByUnitIdAndDate($unitId, $startDate, $endDate);
    }

    /**
     * Check if there are conflicting blocks for the given parameters
     */
    public function hasConflictingBlocks(int $unitId, string $blockDate, string $startTime, string $endTime, ?int $currentBlockId = null): bool
    {
        $conflictingBlocks = $this->scheduleBlockRepository->findConflictingBlocks(
            $unitId,
            $blockDate,
            $startTime,
            $endTime,
            $currentBlockId
        );

        return $conflictingBlocks->isNotEmpty();
    }

    /**
     * Create a new schedule block
     */
    public function createBlock(array $data): ScheduleBlock
    {
        $blockData = array_merge($data, [
            'company_id' => Auth::user()->unit->company->id,
            'unit_id' => Auth::user()->unit->id,
            'user_id' => Auth::id(),
            'active' => true,
        ]);

        // For full day blocks, set start_time and end_time to null
        if ($data['block_type'] === ScheduleBlockTypeEnum::FULL_DAY->value) {
            $blockData['start_time'] = null;
            $blockData['end_time'] = null;
        }

        return $this->scheduleBlockRepository->create($blockData);
    }

    /**
     * Update an existing schedule block
     */
    public function updateBlock(ScheduleBlock $scheduleBlock, array $data): bool
    {
        // For full day blocks, set start_time and end_time to null
        if ($data['block_type'] === ScheduleBlockTypeEnum::FULL_DAY->value) {
            $data['start_time'] = null;
            $data['end_time'] = null;
        }

        return $this->scheduleBlockRepository->update($scheduleBlock, $data);
    }

    /**
     * Delete a schedule block
     */
    public function deleteBlock(ScheduleBlock $scheduleBlock): bool
    {
        return $this->scheduleBlockRepository->delete($scheduleBlock);
    }

    /**
     * Get active blocks for a unit
     */
    public function getActiveBlocksByUnit(int $unitId): Collection
    {
        return $this->scheduleBlockRepository->getActiveBlocksByUnit($unitId);
    }

    public function getActiveBlocksByCompany(int $companyId): Collection
    {
        return $this->scheduleBlockRepository->getActiveBlocksByCompany($companyId);
    }

    public function getBlocksByCompanyAndDate(int $companyId, Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->scheduleBlockRepository->findByCompanyIdAndDate($companyId, $startDate, $endDate);
    }

    /**
     * Deactivate blocks by company ID
     *
     * @param int $companyId
     * @return void
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        $this->scheduleBlockRepository->deactivateByCompanyId($companyId);
    }

    /**
     * Check if a time slot is blocked
     */
    public function isTimeSlotBlocked(int $unitId, string $date, string $startTime, string $endTime): bool
    {
        return $this->hasConflictingBlocks($unitId, $date, $startTime, $endTime);
    }

    /**
     * Get block for a specific time slot
     */
    public function getBlockForTimeSlot(Collection $blocks, string $currentDate, string $currentTime, string $currentEndTime)
    {
        return $blocks->first(function ($block) use ($currentDate, $currentTime, $currentEndTime) {
            if ($block->block_date->format('Y-m-d') !== $currentDate) {
                return false;
            }

            if ($block->block_type === ScheduleBlockTypeEnum::FULL_DAY) {
                return true;
            }

            // For time slot blocks, check if there's any overlap
            return $block->start_time < $currentEndTime && $block->end_time > $currentTime;
        });
    }

    /**
     * Validate and create a new schedule block
     */
    public function validateAndCreateBlock(array $validated): ScheduleBlock
    {
        if ($this->hasConflictingBlocks(
            Auth::user()->unit->id,
            $validated['block_date'],
            $validated['start_time'] ?? '00:00',
            $validated['end_time'] ?? '23:59'
        )) {
            throw new \Exception('Já existe um bloqueio para este horário/dia.');
        }

        return $this->createBlock($validated);
    }

    /**
     * Validate and update an existing schedule block
     */
    public function validateAndUpdateBlock(ScheduleBlock $scheduleBlock, array $validated): bool
    {
        if ($this->hasConflictingBlocks(
            Auth::user()->unit->id,
            $validated['block_date'],
            $validated['start_time'] ?? '00:00',
            $validated['end_time'] ?? '23:59',
            $scheduleBlock->id
        )) {
            throw new \Exception('Já existe um bloqueio para este horário/dia.');
        }

        return $this->updateBlock($scheduleBlock, $validated);
    }
}
