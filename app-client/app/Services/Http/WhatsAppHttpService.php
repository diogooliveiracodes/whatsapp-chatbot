<?php

namespace App\Services\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class WhatsAppHttpService
{
    protected Client $client;
    protected string $baseUrl = 'https://graph.facebook.com/v22.0';

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false,
            'timeout' => 30,
            'connect_timeout' => 10
        ]);
    }

    /**
     * Send POST request to WhatsApp API
     */
    public function post(string $url, array $data, array $headers = []): array
    {
        try {
            $response = $this->client->post($url, [
                RequestOptions::JSON => $data,
                RequestOptions::HEADERS => array_merge([
                    'Content-Type' => 'application/json',
                ], $headers),
            ]);

            $responseData = json_decode($response->getBody(), true);

            Log::info('WhatsApp API response', [
                'url' => $url,
                'status' => $response->getStatusCode(),
                'response' => $responseData
            ]);

            return $responseData;
        } catch (GuzzleException $e) {
            Log::error('WhatsApp API error', [
                'url' => $url,
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return [
                'error' => true,
                'message' => $e->getMessage(),
                'status_code' => $e->getCode()
            ];
        }
    }

    /**
     * Send GET request to WhatsApp API
     */
    public function get(string $url, array $headers = []): array
    {
        try {
            $response = $this->client->get($url, [
                RequestOptions::HEADERS => array_merge([
                    'Content-Type' => 'application/json',
                ], $headers),
            ]);

            $responseData = json_decode($response->getBody(), true);

            Log::info('WhatsApp API GET response', [
                'url' => $url,
                'status' => $response->getStatusCode(),
                'response' => $responseData
            ]);

            return $responseData;
        } catch (GuzzleException $e) {
            Log::error('WhatsApp API GET error', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);

            return [
                'error' => true,
                'message' => $e->getMessage(),
                'status_code' => $e->getCode()
            ];
        }
    }

    /**
     * Build WhatsApp API URL
     */
    public function buildUrl(string $phoneNumberId, string $endpoint = 'messages'): string
    {
        return "{$this->baseUrl}/{$phoneNumberId}/{$endpoint}";
    }
}
