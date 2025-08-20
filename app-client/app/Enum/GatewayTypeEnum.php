<?php

namespace App\Enum;

enum GatewayTypeEnum: int
{
    case ASAAS = 1;
    case MERCADO_PAGO = 2;
    case PAGSEGURO = 3;
    case STRIPE = 4;
    case PAYPAL = 5;

    public function label(): string
    {
        return match($this) {
            self::ASAAS => 'Asaas',
            self::MERCADO_PAGO => 'Mercado Pago',
            self::PAGSEGURO => 'PagSeguro',
            self::STRIPE => 'Stripe',
            self::PAYPAL => 'PayPal',
        };
    }
}
