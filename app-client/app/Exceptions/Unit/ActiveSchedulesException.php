<?php

namespace App\Exceptions\Unit;

use Exception;

class ActiveSchedulesException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('units.error.active_schedules'));
    }
}
