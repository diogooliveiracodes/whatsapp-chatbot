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
use App\Enum\PaymentMethodEnum;
use App\Services\Payment\PaymentService;
use App\Models\AsaasCustomer;
use App\Services\ErrorLog\ErrorLogService;
use Carbon\Carbon;

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
    public function __construct(AsaasPaymentService $asaasPaymentService, PaymentService $paymentService, ErrorLogService $errorLogService)
    {
        $this->asaasPaymentService = $asaasPaymentService;
        $this->paymentService = $paymentService;
        $this->errorLogService = $errorLogService;
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
                'payment_method' => PaymentMethodEnum::PIX->value,
            ]);

            // Associar o pagamento à assinatura através da tabela pivot
            $payment->signatures()->attach($signature->id);

            $asaasResponse = $this->asaasPaymentService->createPixPayment($payment, $asaasCustomer);

            // Salvar o ID do pagamento do Asaas
            if (isset($asaasResponse['id'])) {
                $payment->update(['gateway_payment_id' => $asaasResponse['id']]);
            }

            return $asaasResponse;
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'generateSignaturePayment', 'signature_id' => $signature->id]);
            throw $e;
        }
    }

    /**
     * Check payment status and update signature if paid
     *
     * @param string $asaasPaymentId
     * @return array
     */
    public function checkPaymentStatus(string $asaasPaymentId): array
    {
        try {
            // Buscar o pagamento pelo ID do Asaas
            $payment = $this->paymentService->findByAsaasPaymentId($asaasPaymentId);

            if (!$payment) {
                return [
                    'success' => false,
                    'message' => 'Pagamento não encontrado',
                    'status' => 'not_found'
                ];
            }

            // Verificar status no Asaas
            $asaasResponse = $this->asaasPaymentService->checkPaymentStatus($payment->gateway_payment_id);

            if (!isset($asaasResponse['status'])) {
                return [
                    'success' => false,
                    'message' => 'Erro ao verificar status no Asaas',
                    'status' => 'error'
                ];
            }

            $asaasStatus = $asaasResponse['status'];
            $internalStatus = $this->paymentService->mapAsaasStatusToInternal($asaasStatus);

            // Atualizar status do pagamento
            $this->paymentService->updatePaymentStatus(
                $payment->id,
                $internalStatus,
                $asaasStatus === 'CONFIRMED' ? Carbon::now()->toDateTimeString() : null
            );

            // Se o pagamento foi confirmado, atualizar a assinatura
            if ($internalStatus === PaymentStatusEnum::PAID->value) {
                // Buscar a assinatura através do relacionamento pivot
                $signature = $payment->signatures()->first();
                if ($signature) {
                    $this->updateSignatureAfterPayment($signature);
                }
            }

            return [
                'success' => true,
                'message' => 'Status verificado com sucesso',
                'status' => $asaasStatus,
                'internal_status' => $internalStatus,
                'payment_id' => $payment->id
            ];

        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'checkPaymentStatus', 'asaas_payment_id' => $asaasPaymentId]);

            return [
                'success' => false,
                'message' => 'Erro ao verificar status do pagamento',
                'status' => 'error'
            ];
        }
    }

    /**
     * Update signature after successful payment
     *
     * @param Signature $signature
     * @return void
     */
    public function updateSignatureAfterPayment(Signature $signature): void
    {
        try {
            $plan = $signature->plan;
            $newExpirationDate = $signature->expires_at && $signature->expires_at->isFuture()
                ? $signature->expires_at->addMonths($plan->duration_months)
                : now()->addMonths($plan->duration_months);

            $signature->update([
                'status' => SignatureStatusEnum::PAID->value,
                'expires_at' => $newExpirationDate,
            ]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'updateSignatureAfterPayment', 'signature_id' => $signature->id]);
        }
    }
}
