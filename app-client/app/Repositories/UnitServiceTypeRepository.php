<?php

namespace App\Repositories;

use App\Models\Unit;
use App\Models\UnitServiceType;
use App\Utils\MoneyHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UnitServiceTypeRepository
{
    public function __construct(
        protected UnitServiceType $model
    ) {}

    /**
     * Get all unit service types for the current company
     *
     * @return Collection
     */
    public function getUnitServiceTypes(): Collection
    {
        return $this
            ->model
            ->where('company_id', Auth::user()->company_id)
            ->where('active', true)
            ->get();
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
        $data['price'] = MoneyHelper::parse($data['price']);

        // Process week days - unchecked checkboxes are not sent, so we need to set them as false
        $weekDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($weekDays as $day) {
            // If the checkbox is not in the request, it means it's unchecked (false)
            $data[$day] = isset($data[$day]) && $data[$day] == '1';
        }

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
        $data['price'] = MoneyHelper::parse($data['price']);

        // Process week days - unchecked checkboxes are not sent, so we need to set them as false
        $weekDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($weekDays as $day) {
            // If the checkbox is not in the request, it means it's unchecked (false)
            $data[$day] = isset($data[$day]) && $data[$day] == '1';
        }

        $unitServiceType->update($data);
        return $unitServiceType;
    }

    /**
     * Deactivate a unit service type
     *
     * @param UnitServiceType $unitServiceType
     * @return bool
     */
    public function deactivate(UnitServiceType $unitServiceType): void
    {
        $unitServiceType->update(['active' => false]);
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

    /**
     * Get all deactivated unit service types for the current company
     *
     * @return Collection
     */
    public function getDeactivatedUnitServiceTypes(): Collection
    {
        return $this
            ->model
            ->where('company_id', Auth::user()->company_id)
            ->where('active', false)
            ->get();
    }

    /**
     * Activate a unit service type
     *
     * @param UnitServiceType $unitServiceType
     * @return void
     */
    public function activate(UnitServiceType $unitServiceType): void
    {
        $unitServiceType->update(['active' => true]);
    }

    /**
     * Deactivate unit service types by company ID
     *
     * @param int $companyId
     * @return void
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->where('company_id', $companyId)->update(['active' => false]);
    }
}
