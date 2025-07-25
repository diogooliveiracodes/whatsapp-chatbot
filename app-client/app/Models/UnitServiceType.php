<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitServiceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'unit_id',
        'name',
        'description',
        'price',
        'active',
    ];

    protected $table = 'unit_service_types';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function unit(): belongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}