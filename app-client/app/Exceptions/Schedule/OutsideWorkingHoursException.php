<?php

namespace App\Exceptions\Schedule;

class OutsideWorkingHoursException extends ScheduleException
{
    public function __construct()
    {
        parent::__construct(__('schedules.messages.outside_working_hours'));
    }
}
