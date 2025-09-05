<?php

namespace App\Models;

use App\Helpers\PhoneHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'active',
        'company_id',
        'user_id',
        'unit_id',
        'name',
        'phone',
        'document_number',
        'whatsapp_id',
        'whatsapp_phone_number_id'
    ];

    /**
     * Mutator para o campo phone - remove formatação antes de salvar
     *
     * @param string $value
     * @return void
     */
    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = PhoneHelper::unformat($value);
    }

    /**
     * Accessor para o campo phone - formata para exibição
     *
     * @param string $value
     * @return string
     */
    public function getPhoneAttribute($value): string
    {
        return PhoneHelper::format($value);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chat_sessions(): HasMany
    {
        return $this->hasMany(ChatSession::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
