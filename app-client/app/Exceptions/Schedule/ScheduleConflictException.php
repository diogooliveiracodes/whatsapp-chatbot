<?php

namespace App\Exceptions\Schedule;

class ScheduleConflictException extends ScheduleException
{
    public function __construct()
    {
        parent::__construct(__('schedules.messages.time_conflict'));
    }
}
