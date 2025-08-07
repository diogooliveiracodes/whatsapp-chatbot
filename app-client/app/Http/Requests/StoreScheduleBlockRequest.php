<?php

namespace App\Http\Requests;

use App\Enum\ScheduleBlockTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'block_date' => 'required|date|after_or_equal:today',
            'block_type' => 'required|in:' . implode(',', ScheduleBlockTypeEnum::values()),
            'reason' => 'nullable|string|max:500',
        ];

        // Add conditional validation for time_slot blocks
        if ($this->input('block_type') === ScheduleBlockTypeEnum::TIME_SLOT->value) {
            $rules['start_time'] = 'required|date_format:H:i';
            $rules['end_time'] = 'required|date_format:H:i|after:start_time';
        } else {
            // For full day blocks, make time fields nullable
            $rules['start_time'] = 'nullable';
            $rules['end_time'] = 'nullable';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'block_date.required' => __('schedule-blocks.messages.date_required'),
            'block_date.date' => __('schedule-blocks.messages.invalid_date'),
            'block_date.after_or_equal' => __('schedule-blocks.messages.date_required'),
            'block_type.required' => __('schedule-blocks.messages.block_type_required'),
            'block_type.in' => __('schedule-blocks.messages.block_type_required'),
            'start_time.required' => __('schedule-blocks.messages.start_time_required'),
            'start_time.date_format' => __('schedule-blocks.messages.invalid_time_format'),
            'end_time.required' => __('schedule-blocks.messages.end_time_required'),
            'end_time.date_format' => __('schedule-blocks.messages.invalid_time_format'),
            'end_time.after' => __('schedule-blocks.messages.end_time_after_start'),
            'reason.max' => __('schedule-blocks.messages.reason_max'),
        ];
    }
}
