<?php

namespace App\Services\UnitServiceType;

use App\Models\UnitServiceType;
use App\Repositories\UnitServiceTypeRepository;
use Illuminate\Support\Collection;
use App\Models\Unit;

class UnitServiceTypeService
{
    public function __construct(protected UnitServiceTypeRepository $unitServiceTypeRepository) {}

    /**
     * Get all unit service types for the current company
     *
     * @return Collection
     */
    public function getUnitServiceTypes(): Collection
    {
        return $this->unitServiceTypeRepository->getUnitServiceTypes();
    }

    /**
     * Create a new unit service type
     *
     * @param array $data
     * @return UnitServiceType
     */
    public function create(array $data): UnitServiceType
    {
        return $this->unitServiceTypeRepository->create($data);
    }

    /**
     * Update an existing unit service type
     *
     * @param UnitServiceType $unitServiceType
     * @param array $data
     * @return UnitServiceType
     */
    public function update(UnitServiceType $unitServiceType, array $data): UnitServiceType
    {
        return $this->unitServiceTypeRepository->update($unitServiceType, $data);
    }

    /**
     * Deactivate a unit service type
     *
     * @param UnitServiceType $unitServiceType
     * @return bool
     */
    public function deactivate(UnitServiceType $unitServiceType): void
    {
        $this->unitServiceTypeRepository->deactivate($unitServiceType);
    }

    /**
     * Get all unit service types for a given unit
     *
     * @param Unit $unit
     * @return Collection
     */
    public function getUnitServiceTypesByUnit(Unit $unit): Collection
    {
        return $this->unitServiceTypeRepository->getUnitServiceTypesByUnit($unit);
    }
}
