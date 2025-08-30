<?php

namespace App\Services\CompanySettings;

use App\Models\CompanySettings;
use App\Repositories\CompanySettingsRepository;

class CompanySettingsService
{
    public function __construct(
        protected CompanySettingsRepository $companySettingsRepository
    ) {}

    /**
     * Create company settings
     *
     * @param array $data
     * @return CompanySettings
     */
    public function create(array $data): CompanySettings
    {
        return $this->companySettingsRepository->create($data);
    }

    /**
     * Find company settings by company ID
     *
     * @param int $companyId
     * @return CompanySettings|null
     */
    public function findByCompanyId(int $companyId): ?CompanySettings
    {
        return $this->companySettingsRepository->findByCompanyId($companyId);
    }

    /**
     * Update company settings by company ID
     *
     * @param int $companyId
     * @param array $data
     * @return bool
     */
    public function updateByCompanyId(int $companyId, array $data): bool
    {
        // Adicionar company_id aos dados se não estiver presente
        if (!isset($data['company_id'])) {
            $data['company_id'] = $companyId;
        }

        // Tentar atualizar primeiro
        $updated = $this->companySettingsRepository->updateByCompanyId($companyId, $data);

        // Se não foi atualizado (não existe), criar
        if (!$updated) {
            $this->companySettingsRepository->create($data);
            return true;
        }

        return $updated;
    }
}
