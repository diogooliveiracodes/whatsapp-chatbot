<?php

namespace App\Services\Admin;

use App\Repositories\{
    CompanyRepository,
    UserRepository,
    UserRoleRepository,
    ChatSessionRepository,
    MessageRepository,
    CompanySettingsRepository,
    UnitRepository,
    UnitSettingsRepository,
    UnitServiceTypeRepository,
    CompanySubscriptionRepository,
    CompanyPlanRepository,
    PaymentMethodRepository,
    PaymentRepository,
    CustomerRepository,
    ScheduleRepository,
    ScheduleSettingsRepository
};
use App\Services\ErrorLog\ErrorLogService;
use Illuminate\Support\Facades\DB;

/**
 * Service responsible for deactivating a company and all its related entities.
 *
 * This service handles the complete deactivation process of a company by:
 * - Deactivating all users associated with the company
 * - Deactivating all user roles, chat sessions, and messages
 * - Deactivating company settings and configurations
 * - Deactivating units, unit settings, and service types
 * - Deactivating subscriptions, plans, and payment information
 * - Deactivating customers, schedules, and schedule settings
 * - Finally deactivating the company itself
 *
 * All operations are performed within a database transaction to ensure data consistency.
 * If any operation fails, the entire transaction is rolled back and an error is logged.
 */
class DeactivateCompanyService
{
    /**
     * Create a new DeactivateCompanyService instance.
     *
     * @param CompanyRepository $companyRepository Repository for company operations
     * @param UserRepository $userRepository Repository for user operations
     * @param UserRoleRepository $userRoleRepository Repository for user role operations
     * @param ChatSessionRepository $chatSessionRepository Repository for chat session operations
     * @param MessageRepository $messageRepository Repository for message operations
     * @param CompanySettingsRepository $companySettingsRepository Repository for company settings operations
     * @param UnitRepository $unitRepository Repository for unit operations
     * @param UnitSettingsRepository $unitSettingsRepository Repository for unit settings operations
     * @param UnitServiceTypeRepository $unitServiceTypeRepository Repository for unit service type operations
     * @param CompanySubscriptionRepository $companySubscriptionRepository Repository for company subscription operations
     * @param CompanyPlanRepository $companyPlanRepository Repository for company plan operations
     * @param PaymentMethodRepository $paymentMethodRepository Repository for payment method operations
     * @param PaymentRepository $paymentRepository Repository for payment operations
     * @param CustomerRepository $customerRepository Repository for customer operations
     * @param ScheduleRepository $scheduleRepository Repository for schedule operations
     * @param ScheduleSettingsRepository $scheduleSettingsRepository Repository for schedule settings operations
     * @param ErrorLogService $errorLogService Service for logging errors
     */
    public function __construct(
        protected CompanyRepository $companyRepository,
        protected UserRepository $userRepository,
        protected UserRoleRepository $userRoleRepository,
        protected ChatSessionRepository $chatSessionRepository,
        protected MessageRepository $messageRepository,
        protected CompanySettingsRepository $companySettingsRepository,
        protected UnitRepository $unitRepository,
        protected UnitSettingsRepository $unitSettingsRepository,
        protected UnitServiceTypeRepository $unitServiceTypeRepository,
        protected CompanySubscriptionRepository $companySubscriptionRepository,
        protected CompanyPlanRepository $companyPlanRepository,
        protected PaymentMethodRepository $paymentMethodRepository,
        protected PaymentRepository $paymentRepository,
        protected CustomerRepository $customerRepository,
        protected ScheduleRepository $scheduleRepository,
        protected ScheduleSettingsRepository $scheduleSettingsRepository,
        protected ErrorLogService $errorLogService
    ) {}

    /**
     * Deactivate a company and all its related entities.
     *
     * This method performs a complete deactivation of a company by:
     * 1. Starting a database transaction
     * 2. Verifying the company exists
     * 3. Deactivating all related entities in the following order:
     *    - Users and user roles
     *    - Chat sessions and messages
     *    - Company settings
     *    - Units, unit settings, and service types
     *    - Subscriptions, plans, and payment information
     *    - Customers, schedules, and schedule settings
     * 4. Finally deactivating the company itself
     * 5. Committing the transaction if successful
     * 6. Rolling back and logging errors if any operation fails
     *
     * @param int $companyId The ID of the company to deactivate
     * @return bool Returns true if the deactivation was successful
     * @throws \Exception When the company is not found or when any deactivation operation fails
     */
    public function execute(int $companyId): bool
    {
        try {
            DB::beginTransaction();

            $company = $this->companyRepository->findById($companyId);

            if (!$company) {
                throw new \Exception("Company with ID {$companyId} not found");
            }

            // $this->userRepository->deactivateByCompanyId($companyId);
            // $this->userRoleRepository->deactivateByCompanyId($companyId);
            // $this->chatSessionRepository->deactivateByCompanyId($companyId);
            // $this->messageRepository->deactivateByCompanyId($companyId);
            // $this->companySettingsRepository->deactivateByCompanyId($companyId);
            // $this->unitRepository->deactivateByCompanyId($companyId);
            // $this->unitSettingsRepository->deactivateByCompanyId($companyId);
            // $this->unitServiceTypeRepository->deactivateByCompanyId($companyId);
            // $this->companySubscriptionRepository->deactivateByCompanyId($companyId);
            // $this->companyPlanRepository->deactivateByCompanyId($companyId);
            // $this->paymentMethodRepository->deactivateByCompanyId($companyId);
            // $this->paymentRepository->deactivateByCompanyId($companyId);
            // $this->customerRepository->deactivateByCompanyId($companyId);
            // $this->scheduleRepository->deactivateByCompanyId($companyId);
            // $this->scheduleSettingsRepository->deactivateByCompanyId($companyId);
            $this->companyRepository->deactivate($company);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorLogService->logError($e, [
                'action' => 'deactivate_company',
                'company_id' => $companyId,
            ]);
            throw $e;
        }
    }
}
