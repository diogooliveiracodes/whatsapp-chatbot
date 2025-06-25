<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasUuids;

    protected $fillable = ['schedule_id', 'payment_method_id', 'status', 'amount', 'external_id', 'paid_at', 'expires_at'];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public $timestamps = true;

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
