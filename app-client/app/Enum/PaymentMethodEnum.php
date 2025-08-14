<?php

namespace App\Enum;

enum PaymentMethodEnum: int
{
    case CREDIT_CARD = 1;
    case DEBIT_CARD = 2;
    case PIX = 3;

    public function name(): string
    {
        return match ($this) {
            self::CREDIT_CARD => 'Credit Card',
            self::DEBIT_CARD => 'Debit Card',
            self::PIX => 'Pix',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
