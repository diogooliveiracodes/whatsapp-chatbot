<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\Unit;

class CustomerService
{
    public function getCustomersByUnit(Unit $unit)
    {
        return Customer::where('unit_id', $unit->id)->get();
    }
}
