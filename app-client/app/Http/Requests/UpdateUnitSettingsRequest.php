<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by the UnitSettingsPolicy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'street' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:2'],
            'zipcode' => ['nullable', 'string', 'max:20'],
            'whatsapp_webhook_url' => ['nullable', 'url', 'max:255'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'use_ai_chatbot' => ['boolean'],
            'default_language' => ['nullable', 'string', 'max:5'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'sunday' => ['boolean'],
            'monday_start' => ['nullable', 'date_format:H:i'],
            'monday_end' => ['nullable', 'date_format:H:i'],
            'monday' => ['boolean'],
            'tuesday_start' => ['nullable', 'date_format:H:i'],
            'tuesday_end' => ['nullable', 'date_format:H:i'],
            'tuesday' => ['boolean'],
            'wednesday_start' => ['nullable', 'date_format:H:i'],
            'wednesday_end' => ['nullable', 'date_format:H:i'],
            'wednesday' => ['boolean'],
            'thursday_start' => ['nullable', 'date_format:H:i'],
            'thursday_end' => ['nullable', 'date_format:H:i'],
            'thursday' => ['boolean'],
            'friday_start' => ['nullable', 'date_format:H:i'],
            'friday_end' => ['nullable', 'date_format:H:i'],
            'friday' => ['boolean'],
            'saturday_start' => ['nullable', 'date_format:H:i'],
            'saturday_end' => ['nullable', 'date_format:H:i'],
            'saturday' => ['boolean'],
            'use_ai_chatbot' => ['boolean'],
            'default_language' => ['nullable', 'string', 'max:5'],
            'timezone' => ['nullable', 'string', 'max:50'],
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
            'name.required' => __('validation.required', ['attribute' => __('units.attributes.name')]),
            'name.string' => __('validation.string', ['attribute' => __('units.attributes.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('units.attributes.name'), 'max' => 255]),
            'phone.string' => __('validation.string', ['attribute' => __('company-settings.phone')]),
            'phone.max' => __('validation.max.string', ['attribute' => __('company-settings.phone'), 'max' => 20]),
            'street.string' => __('validation.string', ['attribute' => __('company-settings.street')]),
            'street.max' => __('validation.max.string', ['attribute' => __('company-settings.street'), 'max' => 255]),
            'number.string' => __('validation.string', ['attribute' => __('company-settings.number')]),
            'number.max' => __('validation.max.string', ['attribute' => __('company-settings.number'), 'max' => 20]),
            'complement.string' => __('validation.string', ['attribute' => __('company-settings.complement')]),
            'complement.max' => __('validation.max.string', ['attribute' => __('company-settings.complement'), 'max' => 255]),
            'neighborhood.string' => __('validation.string', ['attribute' => __('company-settings.neighborhood')]),
            'neighborhood.max' => __('validation.max.string', ['attribute' => __('company-settings.neighborhood'), 'max' => 255]),
            'city.string' => __('validation.string', ['attribute' => __('company-settings.city')]),
            'city.max' => __('validation.max.string', ['attribute' => __('company-settings.city'), 'max' => 255]),
            'state.string' => __('validation.string', ['attribute' => __('company-settings.state')]),
            'state.max' => __('validation.max.string', ['attribute' => __('company-settings.state'), 'max' => 2]),
            'zipcode.string' => __('validation.string', ['attribute' => __('company-settings.zipcode')]),
            'zipcode.max' => __('validation.max.string', ['attribute' => __('company-settings.zipcode'), 'max' => 20]),
            'whatsapp_webhook_url.url' => __('validation.url', ['attribute' => __('company-settings.whatsapp_webhook_url')]),
            'whatsapp_webhook_url.max' => __('validation.max.string', ['attribute' => __('company-settings.whatsapp_webhook_url'), 'max' => 255]),
            'whatsapp_number.string' => __('validation.string', ['attribute' => __('company-settings.whatsapp_number')]),
            'whatsapp_number.max' => __('validation.max.string', ['attribute' => __('company-settings.whatsapp_number'), 'max' => 20]),
            'sunday_start.date_format' => __('validation.date_format', ['attribute' => __('company-settings.sunday_start'), 'format' => 'HH:mm:ss']),
            'sunday_end.date_format' => __('validation.date_format', ['attribute' => __('company-settings.sunday_end'), 'format' => 'HH:mm:ss']),
            'sunday.boolean' => __('validation.boolean', ['attribute' => __('company-settings.sunday')]),
            'monday_start.date_format' => __('validation.date_format', ['attribute' => __('company-settings.monday_start'), 'format' => 'HH:mm:ss']),
            'monday_end.date_format' => __('validation.date_format', ['attribute' => __('company-settings.monday_end'), 'format' => 'HH:mm:ss']),
            'monday.boolean' => __('validation.boolean', ['attribute' => __('company-settings.monday')]),
            'tuesday_start.date_format' => __('validation.date_format', ['attribute' => __('company-settings.tuesday_start'), 'format' => 'HH:mm:ss']),
            'tuesday_end.date_format' => __('validation.date_format', ['attribute' => __('company-settings.tuesday_end'), 'format' => 'HH:mm:ss']),
            'tuesday.boolean' => __('validation.boolean', ['attribute' => __('company-settings.tuesday')]),
            'wednesday_start.date_format' => __('validation.date_format', ['attribute' => __('company-settings.wednesday_start'), 'format' => 'HH:mm:ss']),
            'wednesday_end.date_format' => __('validation.date_format', ['attribute' => __('company-settings.wednesday_end'), 'format' => 'HH:mm:ss']),
            'wednesday.boolean' => __('validation.boolean', ['attribute' => __('company-settings.wednesday')]),
            'thursday_start.date_format' => __('validation.date_format', ['attribute' => __('company-settings.thursday_start'), 'format' => 'HH:mm:ss']),
            'thursday_end.date_format' => __('validation.date_format', ['attribute' => __('company-settings.thursday_end'), 'format' => 'HH:mm:ss']),
            'thursday.boolean' => __('validation.boolean', ['attribute' => __('company-settings.thursday')]),
            'friday_start.date_format' => __('validation.date_format', ['attribute' => __('company-settings.friday_start'), 'format' => 'HH:mm:ss']),
            'friday_end.date_format' => __('validation.date_format', ['attribute' => __('company-settings.friday_end'), 'format' => 'HH:mm:ss']),
            'friday.boolean' => __('validation.boolean', ['attribute' => __('company-settings.friday')]),
            'saturday_start.date_format' => __('validation.date_format', ['attribute' => __('company-settings.saturday_start'), 'format' => 'HH:mm:ss']),
            'saturday_end.date_format' => __('validation.date_format', ['attribute' => __('company-settings.saturday_end'), 'format' => 'HH:mm:ss']),
            'saturday.boolean' => __('validation.boolean', ['attribute' => __('company-settings.saturday')]),
            'use_ai_chatbot.boolean' => __('validation.boolean', ['attribute' => __('company-settings.use_ai_chatbot')]),
            'default_language.string' => __('validation.string', ['attribute' => __('company-settings.default_language')]),
            'default_language.max' => __('validation.max.string', ['attribute' => __('company-settings.default_language'), 'max' => 5]),
            'timezone.string' => __('validation.string', ['attribute' => __('company-settings.timezone')]),
            'timezone.max' => __('validation.max.string', ['attribute' => __('company-settings.timezone'), 'max' => 50]),
        ];
    }
}
