<?php

namespace App\Exceptions\Schedule;

class InsideBreakPeriodException extends ScheduleException
{
    public function __construct()
    {
        parent::__construct(__('schedules.messages.inside_break_period'));
    }
}


