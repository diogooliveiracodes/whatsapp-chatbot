<?php

namespace App\Models;

use App\Enum\PaymentGatewayEnum;
use App\Enum\PaymentMethodEnum;
use App\Enum\PaymentServiceEnum;
use App\Enum\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Payment extends Model
{
    use HasUuids;

    protected $fillable = [
        'company_id',
        'schedule_id',
        'signature_id',
        'plan_id',
        'customer_id',
        'user_id',
        'payment_method',
        'gateway',
        'service',
        'status',
        'amount',
        'pix_copy_paste',
        'credit_card_payment_link',
        'payment_receipt_path',
        'paid_at',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'payment_method' => PaymentMethodEnum::class,
        'gateway' => PaymentGatewayEnum::class,
        'service' => PaymentServiceEnum::class,
        'status' => PaymentStatusEnum::class,
    ];

    public $timestamps = true;

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function signature(): BelongsTo
    {
        return $this->belongsTo(Signature::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function signatures(): BelongsToMany
    {
        return $this->belongsToMany(Signature::class);
    }
}
