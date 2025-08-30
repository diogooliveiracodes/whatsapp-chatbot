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
            'whatsapp_verify_token' => ['nullable', 'string', 'max:255'],
            'default_language' => ['nullable', 'string', 'max:5'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'use_ai_chatbot' => ['boolean'],
            'settings_active' => ['boolean'],
            'payment_gateway' => ['nullable', 'integer'],
            'gateway_api_key' => ['nullable', 'string', 'max:255'],
            'pix_key' => ['nullable', 'string', 'max:255'],
            'pix_key_type' => ['nullable', 'integer'],
            'bank_code' => ['nullable', 'string', 'max:10'],
            'bank_agency' => ['nullable', 'string', 'max:20'],
            'bank_account' => ['nullable', 'string', 'max:20'],
            'bank_account_digit' => ['nullable', 'string', 'max:5'],
            'bank_account_type' => ['nullable', 'integer'],
            'account_holder_name' => ['nullable', 'string', 'max:255'],
            'account_holder_document' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'whatsapp_verify_token.max' => __('validation.max.string', ['attribute' => __('company-settings.whatsapp_verify_token'), 'max' => 255]),
            'default_language.max' => __('validation.max.string', ['attribute' => __('company-settings.default_language'), 'max' => 5]),
            'timezone.max' => __('validation.max.string', ['attribute' => __('company-settings.timezone'), 'max' => 50]),
            'use_ai_chatbot.boolean' => __('validation.boolean', ['attribute' => __('company-settings.use_ai_chatbot')]),
            'settings_active.boolean' => __('validation.boolean', ['attribute' => __('company-settings.active')]),
            'gateway_api_key.max' => __('validation.max.string', ['attribute' => __('company-settings.gateway_api_key'), 'max' => 255]),
            'pix_key.max' => __('validation.max.string', ['attribute' => __('company-settings.pix_key'), 'max' => 255]),
            'bank_code.max' => __('validation.max.string', ['attribute' => __('company-settings.bank_code'), 'max' => 10]),
            'bank_agency.max' => __('validation.max.string', ['attribute' => __('company-settings.bank_agency'), 'max' => 20]),
            'bank_account.max' => __('validation.max.string', ['attribute' => __('company-settings.bank_account'), 'max' => 20]),
            'bank_account_digit.max' => __('validation.max.string', ['attribute' => __('company-settings.bank_account_digit'), 'max' => 5]),
            'account_holder_name.max' => __('validation.max.string', ['attribute' => __('company-settings.account_holder_name'), 'max' => 255]),
            'account_holder_document.max' => __('validation.max.string', ['attribute' => __('company-settings.account_holder_document'), 'max' => 20]),
        ];
    }
}
