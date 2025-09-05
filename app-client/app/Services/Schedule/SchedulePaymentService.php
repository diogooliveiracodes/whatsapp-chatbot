<?php

namespace App\Services\Schedule;

use App\Models\Payment;
use App\Models\Schedule;
use App\Models\AsaasCustomer;
use App\Services\Payment\PaymentService;
use App\Services\Payment\AsaasPaymentService;
use App\Services\Payment\AsaasCustomerService;
use App\Services\ErrorLog\ErrorLogService;
use App\Enum\PaymentStatusEnum;
use App\Enum\PaymentGatewayEnum;
use App\Enum\PaymentServiceEnum;
use App\Enum\PaymentMethodEnum;
use App\Enum\AsaasCustomerTypeEnum;

class SchedulePaymentService
{
    public function __construct(
        private PaymentService $paymentService,
        private AsaasPaymentService $asaasPaymentService,
        private AsaasCustomerService $asaasCustomerService,
        private ErrorLogService $errorLogService
    ) {}

    /**
     * Generate payment for schedule
     */
    public function generateSchedulePayment(Schedule $schedule, AsaasCustomer $asaasCustomer, string $companyApiKey): array
    {
        try {
            // Set dynamic API key for this company
            $this->asaasPaymentService->setApiKey($companyApiKey);

            // Check if there's already a pending payment for this schedule
            $existingPayment = $this->findPendingPaymentForSchedule($schedule);

            if ($existingPayment && $existingPayment->gateway_payment_id) {
                // If payment exists and has gateway_payment_id, return it
                return [
                    'id' => $existingPayment->gateway_payment_id,
                    'status' => 'PENDING',
                    'existing_payment' => true,
                    'payment_id' => $existingPayment->id
                ];
            } elseif ($existingPayment && !$existingPayment->gateway_payment_id) {
                // If payment exists but doesn't have gateway_payment_id, delete it and create a new one
                $existingPayment->update(['status' => PaymentStatusEnum::EXPIRED->value]);
            }

            // Load the necessary relationships if not already loaded
            if (!$schedule->relationLoaded('unitServiceType')) {
                $schedule->load('unitServiceType');
            }
            if (!$schedule->relationLoaded('unit')) {
                $schedule->load('unit');
            }


            // Validate service price
            $servicePrice = $schedule->unitServiceType->price ?? 0;

            if ($servicePrice <= 0) {
                throw new \Exception('O serviço selecionado não possui preço válido para pagamento. Preço atual: ' . $servicePrice);
            }

            // Create payment record
            $payment = $this->paymentService->createPayment([
                'company_id' => $schedule->unit->company_id,
                'schedule_id' => $schedule->id,
                'customer_id' => $schedule->customer_id,
                'amount' => $servicePrice,
                'expires_at' => now()->addDays(1),
                'status' => PaymentStatusEnum::PENDING->value,
                'gateway' => PaymentGatewayEnum::ASAAS->value,
                'service' => PaymentServiceEnum::SCHEDULE->value,
                'payment_method' => PaymentMethodEnum::PIX->value,
            ]);

            // Associate payment with schedule
            $payment->schedules()->attach($schedule->id);

            // Create payment in Asaas
            $asaasResponse = $this->asaasPaymentService->createPixPayment($payment, $asaasCustomer);

            // Log Asaas response for debugging
            $this->errorLogService->logError(new \Exception('Asaas response: ' . json_encode($asaasResponse)), [
                'action' => 'generateSchedulePayment',
                'schedule_id' => $schedule->id,
                'payment_id' => $payment->id
            ]);

            // Save Asaas payment ID
            if (isset($asaasResponse['id'])) {
                $payment->update(['gateway_payment_id' => $asaasResponse['id']]);
            } else {
                // Log error if no ID in response
                $this->errorLogService->logError(new \Exception('No payment ID in Asaas response'), [
                    'action' => 'generateSchedulePayment',
                    'schedule_id' => $schedule->id,
                    'payment_id' => $payment->id,
                    'asaas_response' => $asaasResponse
                ]);
            }

            return $asaasResponse;
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'generateSchedulePayment', 'schedule_id' => $schedule->id]);
            throw $e;
        }
    }

    /**
     * Get PIX code for schedule payment
     */
    public function getSchedulePixCode(Schedule $schedule, string $paymentId, string $companyApiKey): array
    {
        try {
            // Set dynamic API key for this company
            $this->asaasPaymentService->setApiKey($companyApiKey);

            $response = $this->asaasPaymentService->getPixCode($paymentId);

            // Extract PIX code from response
            $pixCode = $this->extractPixCodeFromResponse($response);

            if ($pixCode) {
                $this->savePixCodeToPayment($paymentId, $pixCode);
            }

            return $response;
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'getSchedulePixCode', 'schedule_id' => $schedule->id, 'payment_id' => $paymentId]);
            throw $e;
        }
    }

    /**
     * Find pending payment for schedule
     */
    private function findPendingPaymentForSchedule(Schedule $schedule): ?Payment
    {
        return Payment::where('schedule_id', $schedule->id)
            ->where('status', PaymentStatusEnum::PENDING->value)
            ->where('payment_method', PaymentMethodEnum::PIX->value)
            ->where('service', PaymentServiceEnum::SCHEDULE->value)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Extract PIX code from Asaas response
     */
    private function extractPixCodeFromResponse(array $response): ?string
    {
        // Check different possible fields in Asaas response
        $possibleFields = ['payload', 'encodedImage', 'pixCode', 'qrCode', 'copyPaste', 'pixCopyPaste'];

        foreach ($possibleFields as $field) {
            if (isset($response[$field]) && !empty($response[$field])) {
                return $response[$field];
            }
        }

        return null;
    }

    /**
     * Save PIX code to payment record
     */
    private function savePixCodeToPayment(string $asaasPaymentId, string $pixCode): void
    {
        try {
            $payment = $this->paymentService->findByAsaasPaymentId($asaasPaymentId);

            if ($payment) {
                $payment->update(['pix_copy_paste' => $pixCode]);
            }
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'savePixCodeToPayment', 'asaas_payment_id' => $asaasPaymentId]);
        }
    }
}
