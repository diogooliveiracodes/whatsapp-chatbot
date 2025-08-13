<?php

namespace App\Enum;

enum SignatureStatusEnum: int
{
    case PENDING = 1;
    case PAID = 2;
    case REJECTED = 3;
    case EXPIRED = 4;
    case EXPIRING_SOON = 5;

    public function name(): string
    {
        return match ($this) {
            self::PENDING => __('signature.status_pending'),
            self::PAID => __('signature.status_paid'),
            self::REJECTED => __('signature.status_rejected'),
            self::EXPIRED => __('signature.status_expired'),
            self::EXPIRING_SOON => __('signature.status_expiring_soon'),
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
