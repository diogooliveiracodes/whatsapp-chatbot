<?php

namespace App\Helpers;

class AsaasConfigHelper
{
    private static ?string $apiKey = null;

    /**
     * Obtém a URL base da API do Asaas
     */
    public static function getBaseUrl(): string
    {
        return config('services.asaas.environment', 'sandbox') === 'production'
            ? 'https://api.asaas.com'
            : 'https://api-sandbox.asaas.com';
    }

    /**
     * Obtém a chave da API do Asaas
     */
    public static function getApiKey(): string
    {
        return config('services.asaas.api_key');
    }
}
