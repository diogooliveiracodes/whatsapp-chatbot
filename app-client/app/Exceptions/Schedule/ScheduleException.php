<?php

namespace App\Exceptions\Schedule;

use Exception;

class ScheduleException extends Exception
{
    protected $statusCode = 422;

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
