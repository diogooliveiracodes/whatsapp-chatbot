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

class WhatsappWebhookController extends Controller
{
    public function __construct(
        protected ErrorLogService $errorLogService
    ) {}

    public function __invoke(Request $request, Company $company, Unit $unit): JsonResponse
    {
        /** @var UnitSettings|null $unitSettings */
        $unitSettings = UnitSettings::where('company_id', $company->id)
            ->where('unit_id', $unit->id)
            ->first();

        $this->errorLogService->logError(new Exception('teste'), [
            'action' => 'whatsapp_webhook',
            'message' => request()->all(),
            'company_id' => $company->id,
            'level' => 'teste',
            'resolved' => 0,
        ]);

        if (!$unitSettings) {
            return response()->json(['error' => 'Unit settings not found'], 404);
        }

        $mockSecret = 'EAAJZCZA7kZC0sBAKZCZBxZCZA0H1ZCYw0dYZBZBHZCYZB1ZA9ZB8oZCZBZCZBZBZC5hZBZCZB3tZCZAQf4hZBZCZA4nZBZB2ZBZCZA9oZCZB3lZBZBZBZCZA6ZBZBvZBZBZCZA';
        // Optional basic signature/secret validation
        // $providedSecret = $request->header('X-Webhook-Secret');
        // if (!empty($unitSettings->whatsapp_webhook_secret) && $providedSecret !== $unitSettings->whatsapp_webhook_secret) {
        //     return response()->json(['error' => 'Invalid signature'], 401);
        // }
        $providedSecret = $request->header('X-Webhook-Secret');
        if ($providedSecret !== $mockSecret) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }


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
}
