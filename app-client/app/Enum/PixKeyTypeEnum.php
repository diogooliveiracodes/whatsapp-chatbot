<?php

namespace App\Enum;

enum PixKeyTypeEnum: int
{
    case CPF = 1;
    case CNPJ = 2;
    case EMAIL = 3;
    case PHONE = 4;
    case RANDOM = 5;

    public function label(): string
    {
        return match($this) {
            self::CPF => 'CPF',
            self::CNPJ => 'CNPJ',
            self::EMAIL => 'E-mail',
            self::PHONE => 'Telefone',
            self::RANDOM => 'Chave Aleatória',
        };
    }
}
