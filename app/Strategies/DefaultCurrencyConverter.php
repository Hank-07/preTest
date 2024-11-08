<?php

namespace App\Strategies;

class DefaultCurrencyConverter implements CurrencyConverterStrategy
{
    public function convert(float $price, string $currency): float
    {
        if ($currency === 'USD') {
            return $price*31;
        }
        return $price;
    }
}
