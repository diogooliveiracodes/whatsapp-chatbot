<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Services\ErrorLog\ErrorLogService;


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
}
