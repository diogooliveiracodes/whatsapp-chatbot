<?php

namespace App\Exceptions\Customer;

use Exception;

class CustomerException extends Exception
{
    protected $statusCode = 422;

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
