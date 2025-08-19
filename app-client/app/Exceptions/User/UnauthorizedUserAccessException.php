<?php

namespace App\Exceptions\User;

use Exception;
use Illuminate\Support\Facades\Lang;

class UnauthorizedUserAccessException extends Exception
{
    public function __construct(string $message = null)
    {
        $message = $message ?? Lang::get('user.exceptions.unauthorized_access');
        parent::__construct($message);
    }
}
