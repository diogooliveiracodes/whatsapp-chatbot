<?php

namespace App\Services\Unit;

use App\Models\Unit;
use App\Repositories\UnitRepository;
use App\Services\UnitSettings\UnitSettingsService;
use App\Services\UnitServiceType\UnitServiceTypeService;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Create a unit for a specific company (without requiring authenticated user)
     *
     * @param array $data
     * @return Unit
     */
    public function createForCompany(array $data)
    {
        $unit = Unit::create($data);

        // Create unit settings
        $unitSettings = [
            'company_id' => $data['company_id'],
            'unit_id' => $unit->id,
            'name' => $unit->name,
            'active' => true,
            'timezone' => 'America/Sao_Paulo',
            'default_language' => 'pt_BR',
        ];
        $this->unitSettingsService->createForCompany($unitSettings);

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
