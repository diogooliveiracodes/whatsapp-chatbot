<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    protected $table = 'companies';

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function user_roles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
