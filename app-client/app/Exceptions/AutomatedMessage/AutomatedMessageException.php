<?php

namespace App\Exceptions\AutomatedMessage;

use Exception;

class AutomatedMessageException extends Exception
{
    protected $statusCode = 422;

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
