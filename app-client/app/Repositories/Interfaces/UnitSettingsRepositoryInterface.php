<?php

namespace App\Repositories\Interfaces;

use App\Models\UnitSettings;
use Illuminate\Database\Eloquent\Collection;

interface UnitSettingsRepositoryInterface
{
    /**
     * Get all unit settings for the current company
     *
     * @return Collection
     */
    public function getUnitSettingsByAuthUser(): Collection;

    /**
     * Create new unit settings
     *
     * @param array $data
     * @return UnitSettings
     */
    public function create(array $data): UnitSettings;

    /**
     * Update unit settings
     *
     * @param UnitSettings $unitSettings
     * @param array $data
     * @return UnitSettings
     */
    public function update(UnitSettings $unitSettings, array $data): UnitSettings;

    /**
     * Delete unit settings
     *
     * @param UnitSettings $unitSettings
     * @return bool
     */
    public function delete(UnitSettings $unitSettings): bool;

    /**
     * Find unit settings by ID
     *
     * @param int $id
     * @return UnitSettings|null
     */
    public function findById(int $id): ?UnitSettings;
}
