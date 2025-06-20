<?php

namespace App\Services\Unit;

use App\Models\Unit;
use App\Repositories\UnitRepository;
use App\Services\Schedule\ScheduleService;
use App\Services\UnitServiceType\UnitServiceTypeService;
use App\Exceptions\Unit\ActiveSchedulesException;

class DeactivateUnitService
{
    public function __construct(
        protected ScheduleService $scheduleService,
        protected UnitServiceTypeService $unitServiceTypeService,
        protected UnitRepository $unitRepository
    ) {}

    /**
     * Deactivate the specified unit.
     * If the unit has active schedules, it will not be deactivated.
     * If the unit has active service types, it will not be deactivated.
     *
     * @param Unit $unit The unit to deactivate
     * @return void
     * @throws \Exception When there's an error deactivating the unit
     */
    public function deactivate(Unit $unit)
    {
        if ($this->scheduleService->getActiveSchedulesFromNow($unit->id)) {
            throw new ActiveSchedulesException();
        }

        foreach ($unit->UnitServiceTypes as $unitServiceType) {
            $this->unitServiceTypeService->deactivate($unitServiceType);
        }

        $this->unitRepository->deactivate($unit);
    }
}
