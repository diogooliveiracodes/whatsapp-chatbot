<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @property string $message
 * @property string $stack_trace
 * @property string $level
 * @property string $context
 */
class ErrorLog extends Model
{
    /** @use HasFactory<\Database\Factories\ErrorLogFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'unit_id',
        'user_id',
        'message',
        'stack_trace',
        'level',
        'context',
        'resolved',
        'resolved_at',
        'resolved_by'
    ];

    protected $table = 'error_logs';

    protected $casts = [
        'context' => 'array',
        'resolved' => 'boolean',
        'resolved_at' => 'datetime'
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

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}