<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
