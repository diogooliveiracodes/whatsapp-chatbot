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
            self::CREDIT_CARD => __('payments.payment_method_credit_card'),
            self::DEBIT_CARD => __('payments.payment_method_debit_card'),
            self::PIX => __('payments.payment_method_pix'),
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
