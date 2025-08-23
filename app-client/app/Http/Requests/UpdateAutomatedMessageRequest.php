<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use App\Enum\AutomatedMessageTypeEnum;

class UpdateAutomatedMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', AutomatedMessageTypeEnum::values()),
            'content' => 'required|string|max:1000',
            'unit_id' => 'required|exists:units,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('automated-messages.messages.name_required'),
            'name.max' => __('automated-messages.messages.name_max'),
            'type.required' => __('automated-messages.messages.type_required'),
            'type.in' => __('automated-messages.messages.type_invalid'),
            'content.required' => __('automated-messages.messages.content_required'),
            'content.max' => __('automated-messages.messages.content_max'),
            'unit_id.required' => __('automated-messages.messages.unit_required'),
            'unit_id.exists' => __('automated-messages.messages.unit_not_found'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Log the validation errors for debugging
        Log::info('UpdateAutomatedMessageRequest validation failed', [
            'errors' => $validator->errors()->toArray(),
            'request_data' => $this->all(),
        ]);

        throw new HttpResponseException(
            redirect()->back()
                ->withInput()
                ->withErrors($validator)
        );
    }
}
