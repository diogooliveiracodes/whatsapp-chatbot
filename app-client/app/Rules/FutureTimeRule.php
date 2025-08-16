<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class FutureTimeRule implements Rule
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

        // Get the schedule date from the request
        $scheduleDate = request()->input('schedule_date');

        if (!$scheduleDate) {
            return true;
        }

        // Get user timezone
        $userTimezone = Auth::user()->unit->unitSettings->timezone ?? 'UTC';

        // Create datetime in user timezone
        $scheduleDateTime = Carbon::parse($scheduleDate . ' ' . $value, $userTimezone);

        // Get current time in user timezone
        $nowInUserTimezone = Carbon::now($userTimezone);

        // Get today's date in user timezone
        $todayInUserTimezone = $nowInUserTimezone->copy()->startOfDay();

        // Get schedule date in user timezone
        $scheduleDateInUserTimezone = $scheduleDateTime->copy()->startOfDay();

        // Check if schedule date is today and time is in the past
        return !($scheduleDateInUserTimezone->eq($todayInUserTimezone) && $scheduleDateTime->lt($nowInUserTimezone));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('schedules.messages.time_must_be_future');
    }
}
