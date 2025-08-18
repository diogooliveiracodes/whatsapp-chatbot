<?php

namespace App\Exceptions\Customer;

class CustomerHasFutureSchedulesException extends CustomerException
{
    public function __construct()
    {
        parent::__construct(__('customers.error.has_future_schedules'));
    }
}
