<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanySettings extends Model
{
    /** @use HasFactory<\Database\Factories\CompanySettingsFactory> */
    use HasFactory;

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
