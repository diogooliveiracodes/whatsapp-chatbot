<?php

namespace App\Services\Interfaces;

use App\Models\UnitSettings;

interface UnitSettingsServiceInterface
{

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
     * Get unit settings by ID
     *
     * @param int $id
     * @return UnitSettings|null
     */
    public function findById(int $id): ?UnitSettings;

}
