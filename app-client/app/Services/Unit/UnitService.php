<?php

namespace App\Services\Unit;

use App\Repositories\UnitRepository;
use App\Models\Unit;

class UnitService
{
    public function __construct(protected UnitRepository $unitRepository) {}

    public function getUnits()
    {
        return $this->unitRepository->getUnits();
    }

    public function create(array $data)
    {
        return $this->unitRepository->create($data);
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
