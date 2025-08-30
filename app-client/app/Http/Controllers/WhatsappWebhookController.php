<?php

namespace App\Http\Controllers;

use App\Exceptions\Whatsapp\WhatsappValidationException;
use App\Jobs\ProcessWhatsappWebhookJob;
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
        try{
            $this->logError(['message' => 'recebido teste do webhook: '.json_encode($request->all())]);

            $mode = $request->query('hub_mode');
            $challenge = $request->query('hub_challenge');
            $token = $request->query('hub_verify_token');

            $verifyToken = config('services.whatsapp.verify_token');

            if ($mode == 'subscribe' && $token == $verifyToken) {
                $this->logError(['message' => 'Whatsapp webhook company: '.$company->id.' unit: '.$unit->id.' verificado']);

                return response($challenge, 200);
            }

            return response()->json(['error' => 'Invalid signature'], 401);
        } catch (Exception $e) {
            $this->logError(['message' => 'erro no verify: '.$e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function __invoke(Request $request, Company $company, Unit $unit): JsonResponse
    {
        /** @var UnitSettings|null $unitSettings */
        // $unitSettings = UnitSettings::where('company_id', $company->id)
        //     ->where('unit_id', $unit->id)
        //     ->first();

        $this->errorLogService->logError(new Exception(json_encode($request->all())), [
            'action' => 'whatsapp_webhook',
            'resolved' => 0,
            'company_id' => 2,
        ], 'teste', 2);

        return response()->json(['status' => 'ok']);

        // if (!$unitSettings) {
        //     return response()->json(['error' => 'Unit settings not found'], 404);
        // }

        $mockSecret = config('services.whatsapp.verify_token');
        // Optional basic signature/secret validation
        // $providedSecret = $request->header('X-Webhook-Secret');
        // if (!empty($unitSettings->whatsapp_webhook_secret) && $providedSecret !== $unitSettings->whatsapp_webhook_secret) {
        //     return response()->json(['error' => 'Invalid signature'], 401);
        // }

        // Normalize a minimal payload shape; adapt here to real provider payload
        // $from = (string) ($request->input('from') ?? $request->input('sender') ?? '');
        // $text = (string) ($request->input('text') ?? $request->input('message') ?? '');
        // $providerMessageId = (string) ($request->input('message_id') ?? '');

        // if ($from === '' || $text === '') {
        //     throw new WhatsappValidationException(__('messages.whatsapp.invalid_payload'));
        // }

        // ProcessWhatsappWebhookJob::dispatch(
        //     $unitSettings->id,
        //     $from,
        //     $text,
        //     $providerMessageId ?: null
        // );

        return response()->json(['status' => 'ok']);
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
