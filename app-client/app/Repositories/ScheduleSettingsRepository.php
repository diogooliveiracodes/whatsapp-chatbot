<?php

namespace App\Repositories;

use App\Models\ScheduleSettings;
use Illuminate\Database\Eloquent\Collection;

class ScheduleSettingsRepository
{
    public function __construct(
        protected ScheduleSettings $model
    ) {}

    /**
     * Deactivate schedule settings by company ID
     *
     * @param int $companyId
     * @return void
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->whereHas('unit', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->update(['active' => false]);
    }
}
