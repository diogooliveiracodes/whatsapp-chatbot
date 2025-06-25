<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPayment extends Model
{
    use HasUuids;

    protected $fillable = ['company_subscription_id', 'status', 'amount', 'external_id', 'paid_at', 'due_date', 'invoice_url'];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    public $timestamps = true;

    public function companySubscription(): BelongsTo
    {
        return $this->belongsTo(CompanySubscription::class);
    }
}
