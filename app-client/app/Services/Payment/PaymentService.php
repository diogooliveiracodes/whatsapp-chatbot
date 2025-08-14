<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Services\ErrorLog\ErrorLogService;
use App\Enum\PaymentStatusEnum;

class PaymentService
{
    /**
     * PaymentService constructor.
     *
     * @param PaymentRepository $paymentRepository
     * @param ErrorLogService $errorLogService
     */
    public function __construct(
        protected PaymentRepository $paymentRepository,
        protected ErrorLogService $errorLogService
    ) {}

    /**
     * Create a new payment.
     *
     * @param array $data
     * @return Payment
     */
    public function createPayment(array $data): Payment
    {
        try {
            return $this->paymentRepository->createPayment($data);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'createPayment', 'data' => $data]);
            throw $e;
        }
    }

    /**
     * Find payment by Asaas payment ID
     *
     * @param string $asaasPaymentId
     * @return Payment|null
     */
    public function findByAsaasPaymentId(string $asaasPaymentId): ?Payment
    {
        try {
            return $this->paymentRepository->findByAsaasPaymentId($asaasPaymentId);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'findByAsaasPaymentId', 'asaas_payment_id' => $asaasPaymentId]);
            return null;
        }
    }

    /**
     * Update payment status
     *
     * @param int $paymentId
     * @param int $status
     * @param string|null $paidAt
     * @return bool
     */
    public function updatePaymentStatus(int $paymentId, int $status, ?string $paidAt = null): bool
    {
        try {
            return $this->paymentRepository->updatePaymentStatus($paymentId, $status, $paidAt);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'updatePaymentStatus', 'payment_id' => $paymentId, 'status' => $status]);
            return false;
        }
    }

    /**
     * Map Asaas status to internal status
     *
     * @param string $asaasStatus
     * @return int
     */
    public function mapAsaasStatusToInternal(string $asaasStatus): int
    {
        return match (strtolower($asaasStatus)) {
            'confirmed', 'paid', 'received' => PaymentStatusEnum::PAID->value,
            'pending', 'overdue' => PaymentStatusEnum::PENDING->value,
            'rejected', 'cancelled', 'refunded' => PaymentStatusEnum::REJECTED->value,
            'expired' => PaymentStatusEnum::EXPIRED->value,
            default => PaymentStatusEnum::PENDING->value,
        };
    }
}
