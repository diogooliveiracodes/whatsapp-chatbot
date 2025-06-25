<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyPlan extends Model
{
    use HasUuids;

    protected $fillable = ['company_id', 'plan_id', 'started_at', 'ends_at', 'payment_id'];

    protected $casts = [
        'started_at' => 'datetime',
        'ends_at' => 'datetime',
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

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
