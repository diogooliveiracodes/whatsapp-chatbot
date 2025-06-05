<?php

namespace App\Http\Requests;


class StoreScheduleRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'schedule_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'service_type' => 'required|string',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => __('schedules.messages.customer_required'),
            'customer_id.exists' => __('schedules.messages.customer_not_found'),
            'schedule_date.required' => __('schedules.messages.date_required'),
            'schedule_date.date' => __('schedules.messages.invalid_date'),
            'start_time.required' => __('schedules.messages.start_time_required'),
            'start_time.date_format' => __('schedules.messages.invalid_time_format'),
            'service_type.required' => __('schedules.messages.service_type_required'),
        ];
    }
}
