<?php

namespace App\Repositories;

use App\Models\UnitSettings;
use App\Repositories\Interfaces\UnitSettingsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\TimezoneHelper;

class UnitSettingsRepository implements UnitSettingsRepositoryInterface
{
    public function __construct(
        protected UnitSettings $model
    ) {}

    /**
     * Create new unit settings
     *
     * @param array $data
     * @return UnitSettings
     */
    public function create(array $data): UnitSettings
    {
        $data['company_id'] = Auth::user()->company_id;
        $data = $this->convertWorkingHoursToUtc($data);
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
        $data = $this->convertWorkingHoursToUtc($data, $unitSettings);
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

            // Handle break checkbox and times
            $hasBreakKey = $day . '_has_break';
            $breakStartKey = $day . '_break_start';
            $breakEndKey = $day . '_break_end';

            $data[$hasBreakKey] = isset($data[$hasBreakKey]);

            if (!$data[$day]) {
                $data[$day . '_start'] = null;
                $data[$day . '_end'] = null;
                $data[$hasBreakKey] = false;
                $data[$breakStartKey] = null;
                $data[$breakEndKey] = null;
            } else if (!$data[$hasBreakKey]) {
                // If day is enabled but has no break, clear break times
                $data[$breakStartKey] = null;
                $data[$breakEndKey] = null;
            }
        }

        return $data;
    }

    /**
     * Convert submitted working hours (which are in user's/unit timezone) to UTC before persisting.
     * Prefers the timezone provided in the payload; falls back to the authenticated user's unit settings,
     * or a sane default when not available.
     */
    private function convertWorkingHoursToUtc(array $data, ?UnitSettings $currentSettings = null): array
    {
        $preferredTimezone = $data['timezone']
            ?? ($currentSettings?->timezone)
            ?? (Auth::user()->unit->unitSettings->timezone ?? 'America/Sao_Paulo');

        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        foreach ($days as $day) {
            $startKey = $day . '_start';
            $endKey = $day . '_end';
            $breakStartKey = $day . '_break_start';
            $breakEndKey = $day . '_break_end';

            if (!empty($data[$startKey])) {
                $data[$startKey] = TimezoneHelper::convertTimeToUtc($data[$startKey], $preferredTimezone);
            }

            if (!empty($data[$endKey])) {
                $data[$endKey] = TimezoneHelper::convertTimeToUtc($data[$endKey], $preferredTimezone);
            }

            if (!empty($data[$breakStartKey])) {
                $data[$breakStartKey] = TimezoneHelper::convertTimeToUtc($data[$breakStartKey], $preferredTimezone);
            }

            if (!empty($data[$breakEndKey])) {
                $data[$breakEndKey] = TimezoneHelper::convertTimeToUtc($data[$breakEndKey], $preferredTimezone);
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
        return $this
            ->model
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->first();
    }

    /**
     * Deactivate unit settings by company ID
     *
     * @param int $companyId
     * @return void
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->where('company_id', $companyId)->delete();
    }
}
