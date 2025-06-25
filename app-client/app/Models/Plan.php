<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'price', 'features', 'active'];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'active' => 'boolean',
    ];

    public $timestamps = true;

    public function companySubscriptions(): HasMany
    {
        return $this->hasMany(CompanySubscription::class);
    }

    public function companyPlans(): HasMany
    {
        return $this->hasMany(CompanyPlan::class);
    }
}
