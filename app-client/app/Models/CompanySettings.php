<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enum\PaymentGatewayEnum;
use App\Enum\PixKeyTypeEnum;
use App\Enum\BankAccountTypeEnum;

class CompanySettings extends Model
{
    /** @use HasFactory<\Database\Factories\CompanySettingsFactory> */
    use HasFactory;

    protected $table = 'company_settings';
    protected $fillable = [
        'company_id',
        'whatsapp_verify_token',
        'whatsapp_access_token',
        'whatsapp_phone_number_id',
        'whatsapp_business_account_id',
        'default_language',
        'timezone',
        'use_ai_chatbot',
        'active',
        'payment_gateway',
        'gateway_api_key',
        'pix_key',
        'pix_key_type',
        'bank_code',
        'bank_agency',
        'bank_account',
        'bank_account_digit',
        'bank_account_type',
        'account_holder_name',
        'account_holder_document'
    ];

    protected $casts = [
        'payment_gateway' => PaymentGatewayEnum::class,
        'pix_key_type' => PixKeyTypeEnum::class,
        'bank_account_type' => BankAccountTypeEnum::class,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
