<?php

namespace App\Repositories;

use App\Models\UnitSettings;
use App\Repositories\Interfaces\UnitSettingsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class UnitSettingsRepository implements UnitSettingsRepositoryInterface
{
    public function __construct(protected UnitSettings $model) {}

    /**
     * Get all unit settings for the current company
     *
     * @return Collection
     */
    public function getUnitSettingsByAuthUser(): Collection
    {
        return $this->model->where('company_id', Auth::user()->company_id)->get();
    }

    /**
     * Create new unit settings
     *
     * @param array $data
     * @return UnitSettings
     */
    public function create(array $data): UnitSettings
    {
        $data['company_id'] = Auth::user()->company_id;
        return $this->model->create($data);
    }

    /**
     * Update unit settings
     *
     * @param UnitSettings $unitSettings
     * @param array $data
     * @return UnitSettings
     */
    public function update(UnitSettings $unitSettings, array $data): UnitSettings
    {
        $unitSettings->update($data);
        return $unitSettings;
    }

    /**
     * Delete unit settings
     *
     * @param UnitSettings $unitSettings
     * @return bool
     */
    public function delete(UnitSettings $unitSettings): bool
    {
        return $unitSettings->delete();
    }

    /**
     * Find unit settings by ID
     *
     * @param int $id
     * @return UnitSettings|null
     */
    public function findById(int $id): ?UnitSettings
    {
        return $this->model->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->first();
    }
}
