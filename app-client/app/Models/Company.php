<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'document_number',
        'document_type',
        'active',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function user_roles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    public function chat_sessions(): HasMany
    {
        return $this->hasMany(ChatSession::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function companySettings(): HasOne
    {
        return $this->hasOne(CompanySettings::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function unitSettings(): HasMany
    {
        return $this->HasMany(UnitSettings::class);
    }

    public function unitServiceTypes(): HasMany
    {
        return $this->hasMany(UnitServiceType::class);
    }

    public function companySubscriptions(): HasMany
    {
        return $this->hasMany(CompanySubscription::class);
    }

    public function companyPlans(): HasMany
    {
        return $this->hasMany(CompanyPlan::class);
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
