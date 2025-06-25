<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanySubscription extends Model
{
    use HasUuids;

    protected $fillable = ['company_id', 'plan_id', 'status', 'started_at', 'ends_at', 'next_charge_at', 'payment_method'];

    protected $casts = [
        'started_at' => 'datetime',
        'ends_at' => 'datetime',
        'next_charge_at' => 'datetime',
    ];

    public $timestamps = true;

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscriptionPayments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class);
    }
}
