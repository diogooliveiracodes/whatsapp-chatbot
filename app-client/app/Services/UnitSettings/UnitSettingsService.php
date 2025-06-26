<?php

namespace App\Services\UnitSettings;

use App\Repositories\UnitSettingsRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\UnitSettings;
use App\Services\Interfaces\UnitSettingsServiceInterface;

class UnitSettingsService implements UnitSettingsServiceInterface
{
    public function __construct(protected UnitSettingsRepository $unitSettingsRepository) {}

    /**
     * Create new unit settings
     *
     * @param array $data
     * @return UnitSettings
     */
    public function create(array $data): UnitSettings
    {
        return $this->unitSettingsRepository->create($data);
    }

    /**
     * Create new unit settings for a specific company (without requiring authenticated user)
     *
     * @param array $data
     * @return UnitSettings
     */
    public function createForCompany(array $data): UnitSettings
    {
        return $this->unitSettingsRepository->createForCompany($data);
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
        return $this->unitSettingsRepository->update($unitSettings, $data);
    }

    /**
     * Delete unit settings
     *
     * @param UnitSettings $unitSettings
     * @return bool
     */
    public function delete(UnitSettings $unitSettings): bool
    {
        return $this->unitSettingsRepository->delete($unitSettings);
    }

    /**
     * Get unit settings by ID
     *
     * @param int $id
     * @return UnitSettings|null
     */
    public function findById(int $id): ?UnitSettings
    {
        return $this->unitSettingsRepository->findById($id);
    }

}
