<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Unit extends Model
{
    /** @use HasFactory<\Database\Factories\UnitFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'address',
        'city',
    ];

    protected $table = 'units';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function chat_sessions(): HasMany
    {
        return $this->hasMany(ChatSession::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function scheduleSettings(): HasOne
    {
        return $this->hasOne(ScheduleSettings::class);
    }

    public function unitSettings(): HasOne
    {
        return $this->hasOne(UnitSettings::class);
    }

    public function UnitSettingsId(): HasOne
    {
        return $this->hasOne(UnitSettings::class, 'unit_id', 'id')->select(['id', 'unit_id']);
    }
}
