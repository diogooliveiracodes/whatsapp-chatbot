<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsaasCustomer extends Model
{
    protected $fillable = [
        'type',
        'company_id',
        'customer_id',
        'asaas_customer_id',
        'name',
        'cpf_cnpj',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
