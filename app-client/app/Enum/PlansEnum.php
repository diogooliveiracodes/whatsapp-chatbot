<?php

namespace App\Enum;

enum PlansEnum: string
{
    case TRIAL = 1;
    case BASIC = 2;
    case PRO = 3;
    case ENTERPRISE = 4;

    public function name(): string
    {
        return match ($this) {
            self::TRIAL => 'Trial',
            self::BASIC => 'Basic',
            self::PRO => 'Pro',
            self::ENTERPRISE => 'Enterprise',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}