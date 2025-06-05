<?php

namespace App\Repositories;

use App\Models\UnitServiceType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;

class UnitServiceTypeRepository
{
    public function __construct(protected UnitServiceType $model) {}

    /**
     * Get all unit service types for the current company
     *
     * @return Collection
     */
    public function getUnitServiceTypes(): Collection
    {
        return $this->model->where('company_id', Auth::user()->company_id)->get();
    }

    /**
     * Create a new unit service type
     *
     * @param array $data
     * @return UnitServiceType
     */
    public function create(array $data): UnitServiceType
    {
        $data['company_id'] = Auth::user()->company_id;
        return $this->model->create($data);
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
        $unitServiceType->update($data);
        return $unitServiceType;
    }

    /**
     * Delete a unit service type
     *
     * @param UnitServiceType $unitServiceType
     * @return bool
     */
    public function delete(UnitServiceType $unitServiceType): bool
    {
        return $unitServiceType->delete();
    }

    /**
     * Get all unit service types for a given unit
     *
     * @param Unit $unit
     * @return Collection
     */
    public function getUnitServiceTypesByUnit(Unit $unit): Collection
    {
        return $this->model->where('unit_id', $unit->id)->get();
    }
}
