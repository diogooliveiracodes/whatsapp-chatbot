<?php

namespace App\Http\Requests;

use App\Enum\ScheduleStatusEnum;

class UpdateScheduleRequest extends BaseFormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'schedule_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'unit_service_type_id' => 'required|exists:unit_service_types,id',
            'notes' => 'nullable|string',
            'status' => 'required|in:'. implode(',', ScheduleStatusEnum::values()),
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
            'service_type.required' => __('schedules.messages.service_type_required'),
            'status.required' => __('schedules.messages.status_required'),
            'status.in' => __('schedules.messages.invalid_status'),
        ];
    }
}
