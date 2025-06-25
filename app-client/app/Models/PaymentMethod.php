<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasUuids;

    protected $fillable = ['company_id', 'type', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public $timestamps = true;

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
