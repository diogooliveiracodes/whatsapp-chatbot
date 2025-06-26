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
     * Create new unit settings for a specific company (without requiring authenticated user)
     *
     * @param array $data
     * @return UnitSettings
     */
    public function createForCompany(array $data): UnitSettings
    {
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
        $processedData = $this->processDaysData($data);
        $unitSettings->update($processedData);
        return $unitSettings;
    }

    /**
     * Process days data to ensure all days are explicitly set and handle their times
     *
     * @param array $data
     * @return array
     */
    private function processDaysData(array $data): array
    {
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        foreach ($days as $day) {
            $data[$day] = isset($data[$day]);

            if (!$data[$day]) {
                $data[$day . '_start'] = null;
                $data[$day . '_end'] = null;
            }
        }

        return $data;
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
