<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanySettings extends Model
{
    /** @use HasFactory<\Database\Factories\CompanySettingsFactory> */
    use HasFactory;

    protected $table = 'company_settings';
    protected $fillable = [
        'name',
        'identification',
        'phone',
        'whatsapp_webhook_url',
        'whatsapp_number',
        'default_language',
        'timezone',
        'working_hour_start',
        'working_hour_end',
        'working_day_start',
        'working_day_end',
        'use_ai_chatbot',
        'active'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
