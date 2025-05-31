<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'schedules';

    protected $fillable = [
        'unit_id',
        'customer_id',
        'user_id',
        'schedule_date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'service_type',
        'is_confirmed'
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
        'is_confirmed' => 'boolean'
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
