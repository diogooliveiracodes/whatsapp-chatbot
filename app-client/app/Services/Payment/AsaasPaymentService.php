<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\AsaasCustomer;
use App\Helpers\AsaasConfigHelper;
use App\Services\ErrorLog\ErrorLogService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

// <?php
// require_once('vendor/autoload.php');

// $client = new \GuzzleHttp\Client();

// $response = $client->request('POST', 'https://api-sandbox.asaas.com/v3/payments', [
//   'body' => '{"billingType":"PIX","value":30,"dueDate":"2025-08-30","description":"Descrição da cobrança","daysAfterDueDateToRegistrationCancellation":1,"externalReference":"payment_id=id"}',
//   'headers' => [
//     'accept' => 'application/json',
//     'content-type' => 'application/json',
//   ],
// ]);

// echo $response->getBody();

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
     * Log de erro personalizado
     */
    private function logError(string $message, array $context = []): void
    {
        Log::error($message, $context);
        $this->errorLogService->logError(new Exception($message), $context);
    }
}
