<?php

namespace App\Services\Schedule;

use App\Enum\ScheduleBlockTypeEnum;
use App\Models\ScheduleBlock;
use App\Repositories\ScheduleBlockRepository;
use App\Services\Unit\UnitService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ScheduleBlockService
{
    public function __construct(
        private ScheduleBlockRepository $scheduleBlockRepository,
        private UnitService $unitService
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
     * Convert date and time from user timezone to UTC
     *
     * @param array $data
     * @return array
     */
    private function convertToUtc(array $data): array
    {
        // Resolve unit: prefer provided unit_id (for owners), fallback to current user's unit
        $unit = isset($data['unit_id'])
            ? ($this->unitService->getUnits()->firstWhere('id', (int) $data['unit_id']) ?? Auth::user()->unit)
            : Auth::user()->unit;
        $unitSettings = $unit->unitSettings;
        $userTimezone = $unitSettings->timezone ?? 'America/Sao_Paulo';

        // Convert block_date to UTC
        $blockDate = Carbon::parse($data['block_date'], $userTimezone)->setTimezone('UTC');
        $data['block_date'] = $blockDate->format('Y-m-d');

        // Convert start_time and end_time to UTC if they exist
        if (isset($data['start_time']) && $data['start_time']) {
            $dateTimeString = $data['block_date'] . ' ' . $data['start_time'] . ':00';
            $startDateTime = Carbon::parse($dateTimeString, $userTimezone)->setTimezone('UTC');
            $data['start_time'] = $startDateTime->format('H:i');
        }

        if (isset($data['end_time']) && $data['end_time']) {
            $dateTimeString = $data['block_date'] . ' ' . $data['end_time'] . ':00';
            $endDateTime = Carbon::parse($dateTimeString, $userTimezone)->setTimezone('UTC');
            $data['end_time'] = $endDateTime->format('H:i');
        }

        return $data;
    }

    /**
     * Create a new schedule block
     */
    public function createBlock(array $data): ScheduleBlock
    {
        // Convert to UTC before saving
        $utcData = $this->convertToUtc($data);

        // Resolve unit for persistence
        $unit = isset($data['unit_id'])
            ? ($this->unitService->getUnits()->firstWhere('id', (int) $data['unit_id']) ?? Auth::user()->unit)
            : Auth::user()->unit;

        $blockData = array_merge($utcData, [
            'company_id' => $unit->company->id,
            'unit_id' => $unit->id,
            'user_id' => Auth::id(),
            'active' => true,
        ]);

        // For full day blocks, set start_time and end_time to null
        if ($utcData['block_type'] === ScheduleBlockTypeEnum::FULL_DAY->value) {
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
        // Convert to UTC before saving
        $utcData = $this->convertToUtc($data);

        // For full day blocks, set start_time and end_time to null
        if ($utcData['block_type'] === ScheduleBlockTypeEnum::FULL_DAY->value) {
            $utcData['start_time'] = null;
            $utcData['end_time'] = null;
        }

        return $this->scheduleBlockRepository->update($scheduleBlock, $utcData);
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
    public function getBlockForTimeSlot($blocks, string $currentDate, string $currentTime, string $currentEndTime)
    {
        // Convert AnonymousResourceCollection to Collection if needed
        $blocksCollection = $blocks instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection
            ? collect($blocks->toArray(request()))
            : $blocks;



        return $blocksCollection->first(function ($block) use ($currentDate, $currentTime, $currentEndTime) {
            // Handle both resource and model data
            if (is_array($block)) {
                $blockDate = $block['block_date'];
                $blockType = $block['block_type'];
                $startTime = $block['start_time'];
                $endTime = $block['end_time'];
            } else {
                $blockDate = $block->block_date->format('Y-m-d');
                $blockType = $block->block_type;
                $startTime = $block->start_time;
                $endTime = $block->end_time;
            }



            if ($blockDate !== $currentDate) {
                return false;
            }

            if ($blockType === ScheduleBlockTypeEnum::FULL_DAY || $blockType === 'full_day') {
                return true;
            }

            // For time slot blocks, check if there's any overlap
            // Note: startTime and endTime from Resource are already in user timezone
            // Also handle null values for start_time and end_time
            if (!$startTime || !$endTime) {
                return false;
            }
            return $startTime < $currentEndTime && $endTime > $currentTime;
        });
    }

    /**
     * Validate and create a new schedule block
     */
    public function validateAndCreateBlock(array $validated): ScheduleBlock
    {
        // Convert to UTC before validation
        $utcData = $this->convertToUtc($validated);

        // Resolve unit used for validation/conflict
        $unit = isset($validated['unit_id'])
            ? ($this->unitService->getUnits()->firstWhere('id', (int) $validated['unit_id']) ?? Auth::user()->unit)
            : Auth::user()->unit;

        if ($this->hasConflictingBlocks(
            $unit->id,
            $utcData['block_date'],
            $utcData['start_time'] ?? '00:00',
            $utcData['end_time'] ?? '23:59'
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
        // Convert to UTC before validation
        $utcData = $this->convertToUtc($validated);

        // Resolve unit used for validation/conflict (allow changing unit if provided)
        $targetUnit = isset($validated['unit_id'])
            ? ($this->unitService->getUnits()->firstWhere('id', (int) $validated['unit_id']) ?? $scheduleBlock->unit ?? Auth::user()->unit)
            : ($scheduleBlock->unit ?? Auth::user()->unit);

        if ($this->hasConflictingBlocks(
            $targetUnit->id,
            $utcData['block_date'],
            $utcData['start_time'] ?? '00:00',
            $utcData['end_time'] ?? '23:59',
            $scheduleBlock->id
        )) {
            throw new \Exception('Já existe um bloqueio para este horário/dia.');
        }

        return $this->updateBlock($scheduleBlock, $validated);
    }
}
