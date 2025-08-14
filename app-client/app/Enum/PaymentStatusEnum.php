<?php

namespace App\Enum;

enum PaymentStatusEnum: int
{
    case PENDING = 1;
    case PAID = 2;
    case REJECTED = 3;
    case EXPIRED = 4;
    case OVERDUE = 5;

    public function name(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::REJECTED => 'Rejected',
            self::EXPIRED => 'Expired',
            self::OVERDUE => 'Overdue',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
