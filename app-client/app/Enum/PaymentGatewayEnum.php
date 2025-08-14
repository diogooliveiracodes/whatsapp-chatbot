<?php

namespace App\Enum;

enum PaymentGatewayEnum: int
{
    case ASSAS = 1;

    public function name(): string
    {
        return match ($this) {
            self::ASSAS => 'Assas',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
