<?php

namespace App\Enum;

enum AsaasCustomerTypeEnum: int
{
    case COMPANY = 1;
    case CUSTOMER = 2;

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getLabel(int $value): string
    {
        return self::cases()[$value]->name;
    }
}
