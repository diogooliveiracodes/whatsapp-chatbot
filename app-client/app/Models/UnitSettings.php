<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitSettings extends Model
{
    /** @use HasFactory<\Database\Factories\UnitSettingsFactory> */
    use HasFactory;

    protected $table = 'unit_settings';
    protected $fillable = [
        'id',
        'company_id',
        'unit_id',
        'name',
        'phone',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'zipcode',
        'whatsapp_webhook_url',
        'whatsapp_number',
        'default_language',
        'timezone',
        'working_hour_start',
        'working_hour_end',
        'working_day_start',
        'working_day_end',
        'use_ai_chatbot'
    ];

    protected $casts = [
        'working_hour_start' => 'string',
        'working_hour_end' => 'string',
        'use_ai_chatbot' => 'boolean'
    ];

    public function getWorkingHourStartAttribute($value)
    {
        return $value ? date('H:i:s', strtotime($value)) : null;
    }

    public function setWorkingHourStartAttribute($value)
    {
        $this->attributes['working_hour_start'] = $value ? date('H:i:s', strtotime($value)) : null;
    }

    public function getWorkingHourEndAttribute($value)
    {
        return $value ? date('H:i:s', strtotime($value)) : null;
    }

    public function setWorkingHourEndAttribute($value)
    {
        $this->attributes['working_hour_end'] = $value ? date('H:i:s', strtotime($value)) : null;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
