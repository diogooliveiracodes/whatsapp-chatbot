<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'description', 'price', 'duration_months', 'status', 'type'];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_months' => 'integer',
        'status' => 'string',
        'type' => 'string',
    ];

    public $timestamps = true;

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }
}
