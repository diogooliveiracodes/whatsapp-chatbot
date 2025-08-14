<?php

namespace App\Services\Signature;

use App\Models\Signature;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use App\Enum\SignatureStatusEnum;
use App\Services\Payment\AsaasPaymentService;
use App\Enum\PaymentStatusEnum;
use App\Enum\PaymentGatewayEnum;
use App\Enum\PaymentServiceEnum;
use App\Services\Payment\PaymentService;
use App\Models\AsaasCustomer;
use App\Services\ErrorLog\ErrorLogService;

class SignatureService
{
    /**
     * @var AsaasPaymentService
     */
    protected AsaasPaymentService $asaasPaymentService;

    /**
     * @var PaymentService
     */
    protected PaymentService $paymentService;

    /**
     * @var ErrorLogService
     */
    protected ErrorLogService $errorLogService;

    /**
     * SignatureService constructor.
     *
     * @param AsaasPaymentService $asaasPaymentService
     */
    public function __construct(AsaasPaymentService $asaasPaymentService, PaymentService $paymentService)
    {
        $this->asaasPaymentService = $asaasPaymentService;
        $this->paymentService = $paymentService;
    }

    /**
     * Activate the trial of the signature.
     *
     * @param array $data
     * @return void
     */
    public function activateTrial(array $data): void
    {
        $plan = Plan::find($data['plan_id']);

        Signature::create([
            'company_id' => $data['company_id'],
            'plan_id' => $data['plan_id'],
            'status' => SignatureStatusEnum::PAID->value,
            'expires_at' => now()->addMonths($plan->duration_months),
        ]);
    }

    /**
     * Generate the payment for the signature.
     *
     * @param Signature $signature
     * @return array
     */
    public function generateSignaturePayment(Signature $signature, AsaasCustomer $asaasCustomer)
    {
        try {
            $payment = $this->paymentService->createPayment([
                'company_id' => $signature->company_id,
                'plan_id' => $signature->plan_id,
                'amount' => $signature->plan->price,
                'expires_at' => now()->addDays(1),
                'status' => PaymentStatusEnum::PENDING->value,
                'gateway' => PaymentGatewayEnum::ASSAS->value,
                'service' => PaymentServiceEnum::SIGNATURE->value,
            ]);

            return $this->asaasPaymentService->createPixPayment($payment, $asaasCustomer);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'generateSignaturePayment', 'signature_id' => $signature->id]);
            throw $e;
        }
    }
}
