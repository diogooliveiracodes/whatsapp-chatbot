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
        'sunday_start',
        'sunday_end',
        'sunday',
        'monday_start',
        'monday_end',
        'monday',
        'tuesday_start',
        'tuesday_end',
        'tuesday',
        'wednesday_start',
        'wednesday_end',
        'wednesday',
        'thursday_start',
        'thursday_end',
        'thursday',
        'friday_start',
        'friday_end',
        'friday',
        'saturday_start',
        'saturday_end',
        'saturday',
        'use_ai_chatbot',
        'active'
    ];

    protected $casts = [
        'sunday_start' => 'string',
        'sunday_end' => 'string',
        'sunday' => 'boolean',
        'monday_start' => 'string',
        'monday_end' => 'string',
        'monday' => 'boolean',
        'tuesday_start' => 'string',
        'tuesday_end' => 'string',
        'tuesday' => 'boolean',
        'wednesday_start' => 'string',
        'wednesday_end' => 'string',
        'wednesday' => 'boolean',
        'thursday_start' => 'string',
        'thursday_end' => 'string',
        'thursday' => 'boolean',
        'friday_start' => 'string',
        'friday_end' => 'string',
        'friday' => 'boolean',
        'saturday_start' => 'string',
        'saturday_end' => 'string',
        'saturday' => 'boolean',
        'use_ai_chatbot' => 'boolean',
        'active' => 'boolean'
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
