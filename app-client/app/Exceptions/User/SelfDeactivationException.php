<?php

namespace App\Exceptions\User;

use Exception;
use Illuminate\Support\Facades\Lang;

class SelfDeactivationException extends Exception
{
    public function __construct(string $message = null)
    {
        $message = $message ?? Lang::get('user.exceptions.self_deactivation');
        parent::__construct($message);
    }
}
