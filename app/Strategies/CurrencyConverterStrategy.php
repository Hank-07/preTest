<?php

namespace App\Strategies;

interface CurrencyConverterStrategy
{
    public function convert(float $price, string $currency): float;
}
