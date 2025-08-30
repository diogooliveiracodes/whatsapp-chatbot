<?php

namespace App\Enum;

enum PaymentGatewayEnum: int
{
    case ASAAS = 1;

    public function name(): string
    {
        return match ($this) {
            self::ASAAS => 'Asaas',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
