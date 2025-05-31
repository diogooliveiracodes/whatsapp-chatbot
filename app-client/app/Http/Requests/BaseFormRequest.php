<?php

namespace App\Http\Requests;

use App\Services\ErrorLog\ErrorLogService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class BaseFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        // Log the actual validation errors
        $errorLogService = app(ErrorLogService::class);
        $errorLogService->logCustomError(
            'Validation failed',
            [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $this->all(),
                'route' => $this->route()->getName(),
            ]
        );

        // Return a generic message to the user
        throw new HttpResponseException(
            new JsonResponse([
                'success' => false,
                'message' => __('validation.generic.validation_error'),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
