<?php

namespace App\Services\WhatsApp;

use App\Models\CompanySettings;
use App\Services\Http\WhatsAppHttpService;
use App\Services\ErrorLog\ErrorLogService;
use Exception;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function __construct(
        protected WhatsAppHttpService $httpService,
        protected ErrorLogService $errorLogService
    ) {}

    /**
     * Send text message via WhatsApp
     */
    public function sendTextMessage(
        string $phone,
        string $message,
        int $companyId,
        ?int $unitId = null
    ): array {
        try {
            // Get company settings
            $companySettings = $this->getCompanySettings($companyId);
            if (!$companySettings) {
                throw new Exception("Company settings not found for company ID: {$companyId}");
            }

            // Validate WhatsApp configuration
            $this->validateWhatsAppConfig($companySettings);

            // Format phone number
            $formattedPhone = $this->formatPhoneNumber($phone);

            // Prepare message data
            $messageData = [
                'messaging_product' => 'whatsapp',
                'to' => $formattedPhone,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ];

            // Send message
            $url = $this->httpService->buildUrl($companySettings->whatsapp_phone_number_id);
            $headers = [
                'Authorization' => 'Bearer ' . $companySettings->whatsapp_access_token
            ];

            $response = $this->httpService->post($url, $messageData, $headers);

            // Log success
            Log::info('WhatsApp message sent successfully', [
                'phone' => $formattedPhone,
                'company_id' => $companyId,
                'unit_id' => $unitId,
                'response' => $response
            ]);

            return [
                'success' => true,
                'message_id' => $response['messages'][0]['id'] ?? null,
                'response' => $response
            ];

        } catch (Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'whatsapp_send_text_message',
                'phone' => $phone,
                'company_id' => $companyId,
                'unit_id' => $unitId,
                'message' => $message
            ], 'whatsapp_api');

            Log::error('WhatsApp message send error', [
                'phone' => $phone,
                'company_id' => $companyId,
                'unit_id' => $unitId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send template message via WhatsApp
     */
    public function sendTemplateMessage(
        string $phone,
        string $templateName,
        array $templateData = [],
        int $companyId,
        ?int $unitId = null
    ): array {
        try {
            // Get company settings
            $companySettings = $this->getCompanySettings($companyId);
            if (!$companySettings) {
                throw new Exception("Company settings not found for company ID: {$companyId}");
            }

            // Validate WhatsApp configuration
            $this->validateWhatsAppConfig($companySettings);

            // Format phone number
            $formattedPhone = $this->formatPhoneNumber($phone);

            // Prepare template data
            $template = [
                'name' => $templateName,
                'language' => [
                    'code' => $companySettings->default_language ?? 'pt_BR'
                ]
            ];

            // Add template components if provided
            if (!empty($templateData)) {
                $template['components'] = $templateData;
            }

            // Prepare message data
            $messageData = [
                'messaging_product' => 'whatsapp',
                'to' => $formattedPhone,
                'type' => 'template',
                'template' => $template
            ];

            // Send message
            $url = $this->httpService->buildUrl($companySettings->whatsapp_phone_number_id);
            $headers = [
                'Authorization' => 'Bearer ' . $companySettings->whatsapp_access_token
            ];

            $response = $this->httpService->post($url, $messageData, $headers);

            // Log success
            Log::info('WhatsApp template message sent successfully', [
                'phone' => $formattedPhone,
                'company_id' => $companyId,
                'unit_id' => $unitId,
                'template' => $templateName,
                'response' => $response
            ]);

            return [
                'success' => true,
                'message_id' => $response['messages'][0]['id'] ?? null,
                'response' => $response
            ];

        } catch (Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'whatsapp_send_template_message',
                'phone' => $phone,
                'company_id' => $companyId,
                'unit_id' => $unitId,
                'template' => $templateName
            ], 'whatsapp_api');

            Log::error('WhatsApp template message send error', [
                'phone' => $phone,
                'company_id' => $companyId,
                'unit_id' => $unitId,
                'template' => $templateName,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get company settings
     */
    protected function getCompanySettings(int $companyId): ?CompanySettings
    {
        return CompanySettings::where('company_id', $companyId)->first();
    }

    /**
     * Validate WhatsApp configuration
     */
    protected function validateWhatsAppConfig(CompanySettings $companySettings): void
    {
        if (empty($companySettings->whatsapp_access_token)) {
            throw new Exception('WhatsApp access token not configured');
        }

        if (empty($companySettings->whatsapp_phone_number_id)) {
            throw new Exception('WhatsApp phone number ID not configured');
        }
    }

    /**
     * Format phone number for WhatsApp API
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If phone doesn't start with country code, assume Brazil (55)
        if (!str_starts_with($phone, '55') && strlen($phone) <= 11) {
            $phone = '55' . $phone;
        }

        return $phone;
    }

    /**
     * Check if WhatsApp is configured for a company
     */
    public function isConfigured(int $companyId): bool
    {
        $companySettings = $this->getCompanySettings($companyId);

        return $companySettings
            && !empty($companySettings->whatsapp_access_token)
            && !empty($companySettings->whatsapp_phone_number_id);
    }
}
