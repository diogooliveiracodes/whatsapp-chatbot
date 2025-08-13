<?php

namespace App\Helpers;

use App\Enum\SignatureStatusEnum;
use Illuminate\Support\Facades\Auth;

class SignatureHelper
{
    /**
     * Verifica se a assinatura do usuário atual está expirando em breve
     *
     * @return bool
     */
    public static function isExpiringSoon(): bool
    {
        $user = Auth::user();

        if (!$user || !$user->company || !$user->company->signature) {
            return false;
        }

        $signature = $user->company->signature;

        return $signature->status === SignatureStatusEnum::EXPIRING_SOON->value;
    }

}
