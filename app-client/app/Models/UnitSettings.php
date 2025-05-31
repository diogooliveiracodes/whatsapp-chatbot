<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitSettings extends Model
{
    /** @use HasFactory<\Database\Factories\UnitSettingsFactory> */
    use HasFactory;

    protected $fillable = [
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
        'working_hour_start',
        'working_hour_end',
        'working_day_start',
        'working_day_end',
        'use_ai_chatbot'
    ];

    protected $casts = [
        'working_hour_start' => 'datetime',
        'working_hour_end' => 'datetime',
        'use_ai_chatbot' => 'boolean'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
