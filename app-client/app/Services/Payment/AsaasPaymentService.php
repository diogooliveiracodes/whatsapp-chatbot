<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\AsaasCustomer;
use App\Helpers\AsaasConfigHelper;
use App\Services\ErrorLog\ErrorLogService;
use Illuminate\Support\Facades\Http;

class AsaasPaymentService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct(
        private ErrorLogService $errorLogService
    ) {
        $this->baseUrl = AsaasConfigHelper::getBaseUrl();
        $this->apiKey = AsaasConfigHelper::getApiKey();
    }

    /**
     * Criar um pagamento via PIX dinâmico
     */
    public function createPixPayment(Payment $payment, AsaasCustomer $asaasCustomer): array
    {
        try {
            $payload = [
                'customer' => $asaasCustomer->asaas_customer_id,
                'billingType' => 'PIX',
                'value' => $payment->amount,
                'dueDate' => $payment->expires_at?->format('Y-m-d') ?? now()->addDays(1)->format('Y-m-d'),
            ];

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'access_token' => $this->apiKey,
                'content-type' => 'application/json',
            ])->post("{$this->baseUrl}/v3/payments", $payload);

            return $response->json();
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'createPixPayment', 'payment_id' => $payment->id]);
            throw $e;
        }
    }

    /**
     * Obter código PIX de uma transação
     */
    public function getPixCode(string $paymentId): array
    {
        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'access_token' => $this->apiKey,
            ])->get("{$this->baseUrl}/v3/payments/{$paymentId}/pixQrCode");

            return $response->json();
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'getPixCode', 'payment_id' => $paymentId]);
            throw $e;
        }
    }
}
