<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\Services\ErrorLog\ErrorLogService;

class StoreImageRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20000',
            'directory' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => __('validation.required', ['attribute' => __('validation.attributes.image')]),
            'directory.required' => __('validation.required', ['attribute' => __('validation.attributes.directory')]),
            'image.image' => __('validation.image', ['attribute' => __('validation.attributes.image')]),
            'image.mimes' => __('validation.mimes', ['attribute' => __('validation.attributes.image'), 'values' => 'jpeg, png, jpg, gif, svg']),
            'image.max' => __('validation.max.file', ['attribute' => __('validation.attributes.image'), 'max' => 20000]),
        ];
    }

    /**
     * Ensure validation errors are returned as JSON for fetch requests.
     */
    protected function failedValidation(Validator $validator)
    {
        app(ErrorLogService::class)->logCustomError(
            'Validation failed',
            [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $this->all(),
                'route' => $this->route()->getName(),
            ]
        );

        throw new HttpResponseException(
            new JsonResponse([
                'success' => false,
                'message' => __('validation.generic.validation_error'),
                'errors' => $validator->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
