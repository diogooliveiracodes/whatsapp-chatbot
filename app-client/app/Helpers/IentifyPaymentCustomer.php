<?php

namespace App\Helpers;

use App\Models\Company;
use App\Models\Customer;

class IdentifyPaymentCustomer
{
    public static function identify(string $prefixedId): Company|Customer|null {
        if(str_starts_with($prefixedId, 'company_')) {
            return Company::find(str_replace('company_', '', $prefixedId));
        }

        if(str_starts_with($prefixedId, 'customer_')) {
            return Customer::find(str_replace('customer_', '', $prefixedId));
        }

        return null;
    }
}
