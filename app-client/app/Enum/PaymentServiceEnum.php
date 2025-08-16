<?php

namespace App\Enum;

enum PaymentServiceEnum: int
{
    case SCHEDULE = 1;
    case SIGNATURE = 2;

    public function name(): string
    {
        return match ($this) {
            self::SCHEDULE => 'Schedule',
            self::SIGNATURE => 'Signature',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
