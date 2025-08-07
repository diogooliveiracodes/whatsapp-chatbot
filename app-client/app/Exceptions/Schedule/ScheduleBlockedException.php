<?php

namespace App\Exceptions\Schedule;

class ScheduleBlockedException extends ScheduleException
{
    public function __construct()
    {
        parent::__construct(__('schedules.messages.time_blocked'));
    }
}
