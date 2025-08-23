<?php

namespace App\Exceptions\AutomatedMessage;

use Exception;

class AutomatedMessageNotFoundException extends Exception
{
    protected $statusCode = 404;

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
