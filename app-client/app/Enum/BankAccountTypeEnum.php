<?php

namespace App\Enum;

enum BankAccountTypeEnum: int
{
    case CHECKING = 1;
    case SAVINGS = 2;
    case SALARY = 3;
    case PAYMENT = 4;

    public function label(): string
    {
        return match($this) {
            self::CHECKING => 'Conta Corrente',
            self::SAVINGS => 'Conta Poupança',
            self::SALARY => 'Conta Salário',
            self::PAYMENT => 'Conta Pagamento',
        };
    }
}
