<?php

namespace App\Models;

use App\Enum\ScheduleBlockTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleBlock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'schedule_blocks';

    protected $fillable = [
        'company_id',
        'unit_id',
        'user_id',
        'block_date',
        'start_time',
        'end_time',
        'block_type',
        'reason',
        'active'
    ];

    protected $casts = [
        'block_date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
        'block_type' => ScheduleBlockTypeEnum::class,
        'active' => 'boolean'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this block conflicts with a given time range
     */
    public function conflictsWith(string $date, string $startTime, string $endTime): bool
    {
        if ($this->block_date->format('Y-m-d') !== $date) {
            return false;
        }

        if ($this->block_type === ScheduleBlockTypeEnum::FULL_DAY) {
            return true;
        }

        // For time slot blocks, check if there's any overlap
        return $this->start_time < $endTime && $this->end_time > $startTime;
    }

    /**
     * Get the display name for the block type
     */
    public function getBlockTypeLabel(): string
    {
        return $this->block_type->getLabel();
    }
}
