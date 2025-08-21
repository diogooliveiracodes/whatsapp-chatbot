<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Formata um número de telefone brasileiro
     *
     * @param string|null $phone
     * @return string
     */
    public static function format(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        // Remove todos os caracteres não numéricos
        $cleaned = preg_replace('/\D/', '', $phone);

        // Limita a 11 dígitos (formato brasileiro)
        $cleaned = substr($cleaned, 0, 11);

        if (strlen($cleaned) < 10) {
            return $phone; // Retorna o valor original se não tiver dígitos suficientes
        }

        $ddd = substr($cleaned, 0, 2);
        $number = substr($cleaned, 2);

        // Formata baseado no número de dígitos
        if (strlen($number) === 9) {
            // Celular: (99) 99999-9999
            return "({$ddd}) " . substr($number, 0, 5) . "-" . substr($number, 5);
        } elseif (strlen($number) === 8) {
            // Telefone fixo: (99) 9999-9999
            return "({$ddd}) " . substr($number, 0, 4) . "-" . substr($number, 4);
        }

        return $phone; // Retorna o valor original se não conseguir formatar
    }

    /**
     * Remove a formatação de um número de telefone
     *
     * @param string|null $phone
     * @return string
     */
    public static function unformat(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        return preg_replace('/\D/', '', $phone);
    }

    /**
     * Verifica se um número de telefone é válido
     *
     * @param string|null $phone
     * @return bool
     */
    public static function isValid(?string $phone): bool
    {
        if (empty($phone)) {
            return false;
        }

        $cleaned = self::unformat($phone);

        // Deve ter 10 ou 11 dígitos (DDD + número)
        return strlen($cleaned) >= 10 && strlen($cleaned) <= 11;
    }
}
