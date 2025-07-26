<?php

namespace App\Utils;

class MoneyHelper
{
    /**
     * Parse a string value to a float
     *
     * @param string $stringValue
     * @return float
     */
    public static function parse(string $stringValue): float
    {
        $clean = preg_replace('/[^\d,.-]/', '', $stringValue);
        $clean = str_replace(',', '.', $clean);
        return floatval($clean);
    }
}
