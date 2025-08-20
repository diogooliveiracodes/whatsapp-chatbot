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
        'appointment_duration_minutes',
        'sunday_start',
        'sunday_end',
        'sunday',
        'sunday_has_break',
        'sunday_break_start',
        'sunday_break_end',
        'monday_start',
        'monday_end',
        'monday',
        'monday_has_break',
        'monday_break_start',
        'monday_break_end',
        'tuesday_start',
        'tuesday_end',
        'tuesday',
        'tuesday_has_break',
        'tuesday_break_start',
        'tuesday_break_end',
        'wednesday_start',
        'wednesday_end',
        'wednesday',
        'wednesday_has_break',
        'wednesday_break_start',
        'wednesday_break_end',
        'thursday_start',
        'thursday_end',
        'thursday',
        'thursday_has_break',
        'thursday_break_start',
        'thursday_break_end',
        'friday_start',
        'friday_end',
        'friday',
        'friday_has_break',
        'friday_break_start',
        'friday_break_end',
        'saturday_start',
        'saturday_end',
        'saturday',
        'saturday_has_break',
        'saturday_break_start',
        'saturday_break_end',
        'use_ai_chatbot',
        'active'
    ];

    protected $casts = [
        'appointment_duration_minutes' => 'integer',
        'sunday_start' => 'string',
        'sunday_end' => 'string',
        'sunday' => 'boolean',
        'sunday_break_start' => 'string',
        'sunday_break_end' => 'string',
        'sunday_has_break' => 'boolean',
        'monday_start' => 'string',
        'monday_end' => 'string',
        'monday' => 'boolean',
        'monday_break_start' => 'string',
        'monday_break_end' => 'string',
        'monday_has_break' => 'boolean',
        'tuesday_start' => 'string',
        'tuesday_end' => 'string',
        'tuesday' => 'boolean',
        'tuesday_break_start' => 'string',
        'tuesday_break_end' => 'string',
        'tuesday_has_break' => 'boolean',
        'wednesday_start' => 'string',
        'wednesday_end' => 'string',
        'wednesday' => 'boolean',
        'wednesday_break_start' => 'string',
        'wednesday_break_end' => 'string',
        'wednesday_has_break' => 'boolean',
        'thursday_start' => 'string',
        'thursday_end' => 'string',
        'thursday' => 'boolean',
        'thursday_break_start' => 'string',
        'thursday_break_end' => 'string',
        'thursday_has_break' => 'boolean',
        'friday_start' => 'string',
        'friday_end' => 'string',
        'friday' => 'boolean',
        'friday_break_start' => 'string',
        'friday_break_end' => 'string',
        'friday_has_break' => 'boolean',
        'saturday_start' => 'string',
        'saturday_end' => 'string',
        'saturday' => 'boolean',
        'saturday_break_start' => 'string',
        'saturday_break_end' => 'string',
        'saturday_has_break' => 'boolean',
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
