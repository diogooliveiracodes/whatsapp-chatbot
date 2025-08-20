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
            'pix_enabled' => ['boolean'],
            'credit_card_enabled' => ['boolean'],
            'debit_card_enabled' => ['boolean'],
            'cash_enabled' => ['boolean'],
            'appointment_duration_minutes' => ['nullable', 'integer', 'min:1'],
            'sunday' => ['boolean'],
            'sunday_start' => ['nullable', 'required_if:sunday,true', 'date_format:H:i'],
            'sunday_end' => ['nullable', 'required_if:sunday,true', 'date_format:H:i'],
            'sunday_has_break' => ['boolean'],
            'sunday_break_start' => ['nullable', 'required_if:sunday_has_break,true', 'date_format:H:i', 'after:sunday_start', 'before:sunday_end'],
            'sunday_break_end' => ['nullable', 'required_if:sunday_has_break,true', 'date_format:H:i', 'after:sunday_break_start', 'before:sunday_end'],
            'monday_start' => ['nullable', 'required_if:monday,true', 'date_format:H:i'],
            'monday_end' => ['nullable', 'required_if:monday,true', 'date_format:H:i'],
            'monday' => ['boolean'],
            'monday_has_break' => ['boolean'],
            'monday_break_start' => ['nullable', 'required_if:monday_has_break,true', 'date_format:H:i', 'after:monday_start', 'before:monday_end'],
            'monday_break_end' => ['nullable', 'required_if:monday_has_break,true', 'date_format:H:i', 'after:monday_break_start', 'before:monday_end'],
            'tuesday_start' => ['nullable', 'required_if:tuesday,true', 'date_format:H:i'],
            'tuesday_end' => ['nullable', 'required_if:tuesday,true', 'date_format:H:i'],
            'tuesday' => ['boolean'],
            'tuesday_has_break' => ['boolean'],
            'tuesday_break_start' => ['nullable', 'required_if:tuesday_has_break,true', 'date_format:H:i', 'after:tuesday_start', 'before:tuesday_end'],
            'tuesday_break_end' => ['nullable', 'required_if:tuesday_has_break,true', 'date_format:H:i', 'after:tuesday_break_start', 'before:tuesday_end'],
            'wednesday_start' => ['nullable', 'required_if:wednesday,true', 'date_format:H:i'],
            'wednesday_end' => ['nullable', 'required_if:wednesday,true', 'date_format:H:i'],
            'wednesday' => ['boolean'],
            'wednesday_has_break' => ['boolean'],
            'wednesday_break_start' => ['nullable', 'required_if:wednesday_has_break,true', 'date_format:H:i', 'after:wednesday_start', 'before:wednesday_end'],
            'wednesday_break_end' => ['nullable', 'required_if:wednesday_has_break,true', 'date_format:H:i', 'after:wednesday_break_start', 'before:wednesday_end'],
            'thursday_start' => ['nullable', 'required_if:thursday,true', 'date_format:H:i'],
            'thursday_end' => ['nullable', 'required_if:thursday,true', 'date_format:H:i'],
            'thursday' => ['boolean'],
            'thursday_has_break' => ['boolean'],
            'thursday_break_start' => ['nullable', 'required_if:thursday_has_break,true', 'date_format:H:i', 'after:thursday_start', 'before:thursday_end'],
            'thursday_break_end' => ['nullable', 'required_if:thursday_has_break,true', 'date_format:H:i', 'after:thursday_break_start', 'before:thursday_end'],
            'friday_start' => ['nullable', 'required_if:friday,true', 'date_format:H:i'],
            'friday_end' => ['nullable', 'required_if:friday,true', 'date_format:H:i'],
            'friday' => ['boolean'],
            'friday_has_break' => ['boolean'],
            'friday_break_start' => ['nullable', 'required_if:friday_has_break,true', 'date_format:H:i', 'after:friday_start', 'before:friday_end'],
            'friday_break_end' => ['nullable', 'required_if:friday_has_break,true', 'date_format:H:i', 'after:friday_break_start', 'before:friday_end'],
            'saturday_start' => ['nullable', 'required_if:saturday,true', 'date_format:H:i'],
            'saturday_end' => ['nullable', 'required_if:saturday,true', 'date_format:H:i'],
            'saturday' => ['boolean'],
            'saturday_has_break' => ['boolean'],
            'saturday_break_start' => ['nullable', 'required_if:saturday_has_break,true', 'date_format:H:i', 'after:saturday_start', 'before:saturday_end'],
            'saturday_break_end' => ['nullable', 'required_if:saturday_has_break,true', 'date_format:H:i', 'after:saturday_break_start', 'before:saturday_end'],
            'use_ai_chatbot' => ['boolean'],
            'default_language' => ['nullable', 'string', 'max:5'],
            'timezone' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if at least one payment method is enabled
            $pixEnabled = $this->boolean('pix_enabled');
            $creditCardEnabled = $this->boolean('credit_card_enabled');
            $debitCardEnabled = $this->boolean('debit_card_enabled');
            $cashEnabled = $this->boolean('cash_enabled');

            if (!$pixEnabled && !$creditCardEnabled && !$debitCardEnabled && !$cashEnabled) {
                $validator->errors()->add('payment_methods', __('unitSettings.at_least_one_payment_method'));
            }
        });
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Convert checkbox values to boolean
        $this->merge([
            'pix_enabled' => $this->boolean('pix_enabled'),
            'credit_card_enabled' => $this->boolean('credit_card_enabled'),
            'debit_card_enabled' => $this->boolean('debit_card_enabled'),
            'cash_enabled' => $this->boolean('cash_enabled'),
        ]);
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
            'pix_enabled.boolean' => __('validation.boolean', ['attribute' => __('unitSettings.pix_enabled')]),
            'credit_card_enabled.boolean' => __('validation.boolean', ['attribute' => __('unitSettings.credit_card_enabled')]),
            'debit_card_enabled.boolean' => __('validation.boolean', ['attribute' => __('unitSettings.debit_card_enabled')]),
            'cash_enabled.boolean' => __('validation.boolean', ['attribute' => __('unitSettings.cash_enabled')]),
            'sunday_start.required_if' => __('unitSettings.validation.sunday_start_required'),
            'sunday_end.required_if' => __('unitSettings.validation.sunday_end_required'),
            'sunday_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.sunday_start'), 'format' => 'HH:mm']),
            'sunday_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.sunday_end'), 'format' => 'HH:mm']),
            'sunday_break_start.required_if' => __('unitSettings.validation.sunday_break_start_required'),
            'sunday_break_end.required_if' => __('unitSettings.validation.sunday_break_end_required'),
            'sunday_break_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.sunday_break_start'), 'format' => 'HH:mm']),
            'sunday_break_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.sunday_break_end'), 'format' => 'HH:mm']),
            'sunday.boolean' => __('validation.boolean', ['attribute' => __('unitSettings.sunday')]),
            'monday_start.required_if' => __('unitSettings.validation.monday_start_required'),
            'monday_end.required_if' => __('unitSettings.validation.monday_end_required'),
            'monday_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.monday_start'), 'format' => 'HH:mm']),
            'monday_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.monday_end'), 'format' => 'HH:mm']),
            'monday_break_start.required_if' => __('unitSettings.validation.monday_break_start_required'),
            'monday_break_end.required_if' => __('unitSettings.validation.monday_break_end_required'),
            'monday_break_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.monday_break_start'), 'format' => 'HH:mm']),
            'monday_break_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.monday_break_end'), 'format' => 'HH:mm']),
            'monday.boolean' => __('validation.boolean', ['attribute' => __('unitSettings.monday')]),
            'tuesday_start.required_if' => __('unitSettings.validation.tuesday_start_required'),
            'tuesday_end.required_if' => __('unitSettings.validation.tuesday_end_required'),
            'tuesday_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.tuesday_start'), 'format' => 'HH:mm']),
            'tuesday_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.tuesday_end'), 'format' => 'HH:mm']),
            'tuesday_break_start.required_if' => __('unitSettings.validation.tuesday_break_start_required'),
            'tuesday_break_end.required_if' => __('unitSettings.validation.tuesday_break_end_required'),
            'tuesday_break_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.tuesday_break_start'), 'format' => 'HH:mm']),
            'tuesday_break_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.tuesday_break_end'), 'format' => 'HH:mm']),
            'tuesday.boolean' => __('validation.boolean', ['attribute' => __('unitSettings.tuesday')]),
            'wednesday_start.required_if' => __('unitSettings.validation.wednesday_start_required'),
            'wednesday_end.required_if' => __('unitSettings.validation.wednesday_end_required'),
            'wednesday_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.wednesday_start'), 'format' => 'HH:mm']),
            'wednesday_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.wednesday_end'), 'format' => 'HH:mm']),
            'wednesday_break_start.required_if' => __('unitSettings.validation.wednesday_break_start_required'),
            'wednesday_break_end.required_if' => __('unitSettings.validation.wednesday_break_end_required'),
            'wednesday_break_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.wednesday_break_start'), 'format' => 'HH:mm']),
            'wednesday_break_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.wednesday_break_end'), 'format' => 'HH:mm']),
            'wednesday.boolean' => __('validation.boolean', ['attribute' => __('unitSettings.wednesday')]),
            'thursday_start.required_if' => __('unitSettings.validation.thursday_start_required'),
            'thursday_end.required_if' => __('unitSettings.validation.thursday_end_required'),
            'thursday_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.thursday_start'), 'format' => 'HH:mm']),
            'thursday_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.thursday_end'), 'format' => 'HH:mm']),
            'thursday_break_start.required_if' => __('unitSettings.validation.thursday_break_start_required'),
            'thursday_break_end.required_if' => __('unitSettings.validation.thursday_break_end_required'),
            'thursday_break_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.thursday_break_start'), 'format' => 'HH:mm']),
            'thursday_break_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.thursday_break_end'), 'format' => 'HH:mm']),
            'thursday.boolean' => __('validation.boolean', ['attribute' => __('unitSettings.thursday')]),
            'friday_start.required_if' => __('unitSettings.validation.friday_start_required'),
            'friday_end.required_if' => __('unitSettings.validation.friday_end_required'),
            'friday_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.friday_start'), 'format' => 'HH:mm']),
            'friday_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.friday_end'), 'format' => 'HH:mm']),
            'friday_break_start.required_if' => __('unitSettings.validation.friday_break_start_required'),
            'friday_break_end.required_if' => __('unitSettings.validation.friday_break_end_required'),
            'friday_break_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.friday_break_start'), 'format' => 'HH:mm']),
            'friday_break_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.friday_break_end'), 'format' => 'HH:mm']),
            'friday.boolean' => __('validation.boolean', ['attribute' => __('unitSettings.friday')]),
            'saturday_start.required_if' => __('unitSettings.validation.saturday_start_required'),
            'saturday_end.required_if' => __('unitSettings.validation.saturday_end_required'),
            'saturday_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.saturday_start'), 'format' => 'HH:mm']),
            'saturday_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.saturday_end'), 'format' => 'HH:mm']),
            'saturday_break_start.required_if' => __('unitSettings.validation.saturday_break_start_required'),
            'saturday_break_end.required_if' => __('unitSettings.validation.saturday_break_end_required'),
            'saturday_break_start.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.saturday_break_start'), 'format' => 'HH:mm']),
            'saturday_break_end.date_format' => __('validation.date_format', ['attribute' => __('unitSettings.saturday_break_end'), 'format' => 'HH:mm']),
            'saturday.boolean' => __('validation.boolean', ['attribute' => __('unitSettings.saturday')]),
            'use_ai_chatbot.boolean' => __('validation.boolean', ['attribute' => __('company-settings.use_ai_chatbot')]),
            'default_language.string' => __('validation.string', ['attribute' => __('company-settings.default_language')]),
            'default_language.max' => __('validation.max.string', ['attribute' => __('company-settings.default_language'), 'max' => 5]),
            'timezone.string' => __('validation.string', ['attribute' => __('company-settings.timezone')]),
            'timezone.max' => __('validation.max.string', ['attribute' => __('company-settings.timezone'), 'max' => 50]),
        ];
    }
}
