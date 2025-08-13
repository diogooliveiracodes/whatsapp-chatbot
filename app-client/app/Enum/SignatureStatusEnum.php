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
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::REJECTED => 'Rejected',
            self::EXPIRED => 'Expired',
            self::EXPIRING_SOON => 'Expiring Soon',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
