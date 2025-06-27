<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleSettings extends Model
{
    use HasFactory;

    protected $table = 'schedule_settings';

    protected $fillable = [
        'unit_id',
        'working_hours_start',
        'working_hours_end',
        'slot_duration_minutes',
        'break_start',
        'break_end',
        'max_appointments_per_day',
        'min_notice_hours',
        'max_advance_days',
        'active'
    ];

    protected $casts = [
        'working_hours_start' => 'datetime',
        'working_hours_end' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        'active' => 'boolean'
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
