<?php

namespace App\Exceptions\Schedule;

class PastScheduleException extends ScheduleException
{
    public function __construct()
    {
        parent::__construct(__('schedules.messages.past_schedule'));
    }
}
