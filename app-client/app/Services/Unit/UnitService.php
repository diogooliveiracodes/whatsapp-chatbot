<?php

namespace App\Services\Unit;

use App\Repositories\UnitRepository;
use App\Models\Unit;
use App\Services\UnitSettings\UnitSettingsService;
use Illuminate\Support\Facades\Auth;
use App\Services\UnitServiceType\UnitServiceTypeService;

class UnitService
{
    public function __construct(
        protected UnitRepository $unitRepository,
        protected UnitSettingsService $unitSettingsService,
        protected UnitServiceTypeService $unitServiceTypeService
    ) {}

    public function getUnits()
    {
        return $this->unitRepository->getUnits();
    }

    public function create(array $data)
    {
        $unit = $this->unitRepository->create($data);
        $unitSettings = [
            'company_id' => Auth::user()->company_id,
            'unit_id' => $unit->id,
            'name' => $unit->name,
            'active' => true,
        ];
        $this->unitSettingsService->create($unitSettings);
        return $unit->load('UnitSettings');
    }

    public function update(Unit $unit, array $data)
    {
        if (!isset($data['active'])) {
            $data['active'] = false;
        }
        return $this->unitRepository->update($unit, $data);
    }

    public function deactivate(Unit $unit)
    {
        foreach ($unit->UnitServiceTypes as $unitServiceType) {
            $this->unitServiceTypeService->deactivate($unitServiceType);
        }
        return $this->unitRepository->deactivate($unit);
    }

    public function getDeactivatedUnits()
    {
        return $this->unitRepository->getDeactivatedUnits();
    }

    public function activate(Unit $unit)
    {
        return $this->unitRepository->activate($unit);
    }
}
