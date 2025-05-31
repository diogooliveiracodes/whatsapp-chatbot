<?php

namespace App\Services\Http;

use Illuminate\Http\JsonResponse;

class HttpResponseService
{
    public function success(string $message, array $data = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function error(string $message, int $statusCode = 500, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }

    public function exception(\Exception $exception, int $statusCode = 500): JsonResponse
    {
        return $this->error(
            $exception->getMessage(),
            $statusCode
        );
    }
}
