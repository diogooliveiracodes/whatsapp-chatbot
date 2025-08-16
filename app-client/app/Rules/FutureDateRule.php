<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class FutureDateRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$value) {
            return true;
        }

        // Get user timezone
        $userTimezone = Auth::user()->unit->unitSettings->timezone ?? 'UTC';

        // Create date in user timezone
        $scheduleDate = Carbon::parse($value, $userTimezone)->startOfDay();

        // Get today's date in user timezone
        $todayInUserTimezone = Carbon::now($userTimezone)->startOfDay();

        // Check if schedule date is not in the past
        return !$scheduleDate->lt($todayInUserTimezone);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('schedules.messages.date_must_be_today_or_future');
    }
}
