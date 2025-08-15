<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'schedule_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'unit_service_type_id' => 'required|exists:unit_service_types,id',
            'notes' => 'nullable|string|max:1000',
        ];
    }

        public function messages(): array
    {
        return [
            'customer_id.required' => __('schedules.messages.customer_required'),
            'customer_id.exists' => __('schedules.messages.customer_not_found'),
            'schedule_date.required' => __('schedules.messages.date_required'),
            'schedule_date.date' => __('schedules.messages.invalid_date'),
            'schedule_date.after_or_equal' => __('schedules.messages.date_must_be_today_or_future'),
            'start_time.required' => __('schedules.messages.start_time_required'),
            'start_time.date_format' => __('schedules.messages.invalid_time_format'),
            'unit_service_type_id.required' => __('schedules.messages.service_type_required'),
            'unit_service_type_id.exists' => __('schedules.messages.service_type_not_found'),
            'notes.max' => __('schedules.messages.notes_too_long'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Log the validation errors for debugging
        Log::info('StoreScheduleRequest validation failed', [
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
