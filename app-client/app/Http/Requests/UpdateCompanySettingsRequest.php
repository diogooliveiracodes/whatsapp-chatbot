<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanySettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_id' => ['sometimes', 'exists:companies,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'identification' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp_webhook_url' => ['nullable', 'string', 'url', 'max:255'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'default_language' => ['nullable', 'string', 'max:5'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'working_hour_start' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'working_hour_end' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'working_day_start' => ['nullable', Rule::in([1, 2, 3, 4, 5, 6, 7])],
            'working_day_end' => ['nullable', Rule::in([1, 2, 3, 4, 5, 6, 7])],
            'use_ai_chatbot' => ['boolean'],
        ];
    }
}
