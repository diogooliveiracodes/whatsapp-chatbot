<?php

namespace App\Models;

use App\Enum\AutomatedMessageTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutomatedMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'automated_messages';

    protected $fillable = [
        'company_id',
        'unit_id',
        'user_id',
        'name',
        'type',
        'content',
        'is_active'
    ];

    protected $casts = [
        'type' => AutomatedMessageTypeEnum::class,
        'is_active' => 'boolean'
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
     * Get the display name for the message type
     */
    public function getTypeLabel(): string
    {
        return $this->type->getLabel();
    }

    /**
     * Get the description for the message type
     */
    public function getTypeDescription(): string
    {
        return $this->type->getDescription();
    }

    /**
     * Scope to get only active messages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get messages by type
     */
    public function scopeByType($query, AutomatedMessageTypeEnum $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get messages by unit
     */
    public function scopeByUnit($query, int $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    /**
     * Scope to get messages by company
     */
    public function scopeByCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
