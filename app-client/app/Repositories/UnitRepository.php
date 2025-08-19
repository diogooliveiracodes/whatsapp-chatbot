<?php

namespace App\Repositories;

use App\Models\Unit;
use App\Services\UnitSettings\UnitSettingsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class UnitRepository
{
    public function __construct(
        protected Unit $model,
        protected UnitSettingsService $unitSettingsService
    ) {}

    /**
     * Get all units for the current company
     *
     * @return Collection
     */
    public function getUnits(): Collection
    {
        return $this
            ->model
            ->where('company_id', Auth::user()->company_id)
            ->where('active', true)
            ->get();
    }

    /**
     * Create a new unit
     *
     * @param array $data
     * @return Unit
     */
    public function create(array $data): Unit
    {
        $data['company_id'] = Auth::user()->company_id;
        $unit = $this->model->create($data);
        $this->unitSettingsService->create([
            'unit_id' => $unit->id,
            'company_id' => Auth::user()->company_id,
            'name' => $unit->name,
            'default_language' => 'pt_BR',
            'timezone' => 'America/Sao_Paulo',
        ]);
        return $unit;
    }

    /**
     * Update an existing unit
     *
     * @param Unit $unit
     * @param array $data
     * @return Unit
     */
    public function update(Unit $unit, array $data): Unit
    {
        if (!$data['active']) {
            $data['active'] = 0;
        }
        $unit->update($data);
        return $unit;
    }

    /**
     * Deactivate a unit
     *
     * @param Unit $unit
     * @return bool
     */
    public function deactivate(Unit $unit): void
    {
        $unit->update(['active' => false]);
    }

    /**
     * Find a unit by ID
     *
     * @param int $id
     * @return Unit|null
     */
    public function findById(int $id): ?Unit
    {
        return $this->model->find($id);
    }

    /**
     * Find a unit by ID and company
     *
     * @param int $id
     * @return Unit|null
     */
    public function findByIdAndCompany(int $id): ?Unit
    {
        return $this
            ->model
            ->where('id', $id)
            ->where('company_id', Auth::user()->company_id)
            ->first();
    }

    public function getDeactivatedUnits(): Collection
    {
        return $this
            ->model
            ->where('company_id', Auth::user()->company_id)
            ->where('active', false)
            ->get();
    }

    public function activate(Unit $unit): void
    {
        $unit->update(['active' => true]);
    }

    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->where('company_id', $companyId)->update(['active' => false]);
    }
}
