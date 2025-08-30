<?php

namespace App\Http\Controllers;

use App\Exceptions\Whatsapp\WhatsappValidationException;
use App\Jobs\ProcessWhatsappWebhookJob;
use App\Jobs\WhatsappWebhookProcessReceivedMessageJob;
use App\Models\Company;
use App\Models\Unit;
use App\Models\UnitSettings;
use App\Services\ErrorLog\ErrorLogService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WhatsappWebhookController extends Controller
{
    public function __construct(
        protected ErrorLogService $errorLogService
    ) {}

    /**
     * Valida o webhook do WhatsApp conforme documentação oficial
     */
    public function verify(Request $request, Company $company, Unit $unit)
    {
        try {
            $this->logError(['message' => 'recebido teste do webhook: ' . json_encode($request->all())]);

            $mode = $request->query('hub_mode');
            $challenge = $request->query('hub_challenge');
            $token = $request->query('hub_verify_token');

            $verifyToken = config('services.whatsapp.verify_token');

            if ($mode == 'subscribe' && $token == $verifyToken) {
                $this->logError(['message' => 'Whatsapp webhook company: ' . $company->id . ' unit: ' . $unit->id . ' verificado']);

                return response($challenge, 200);
            }

            return response()->json(['error' => 'Invalid signature'], 401);
        } catch (Exception $e) {
            $this->logError(['message' => 'erro no verify: ' . $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function __invoke(Request $request, Company $company, Unit $unit): JsonResponse
    {
        try {
            $this->logError(['message' => 'recebido mensagem no webhook company: ' . $company->id . ' unit: ' . $unit->id . ' ' . json_encode($request->all())]);
            WhatsappWebhookProcessReceivedMessageJob::dispatch($request->all(), $company->id, $unit->id);

            return response()->json(['status' => 'ok']);
        } catch (Exception $e) {
            $this->logError(['message' => 'erro no verify: ' . $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    private function logError(array $data): void
    {
        $this->errorLogService->logError(new Exception($data['message']), [
            'action' => 'whatsapp_webhook',
            'resolved' => 0,
            'company_id' => $data['company_id'] ?? 1,
        ], 'whatsapp_webhook');
    }
}
