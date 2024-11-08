<?php

namespace App\Services;

use App\Strategies\CurrencyConverterStrategy;
use Illuminate\Validation\ValidationException;

class OrderService
{
    protected $currencyConverter;

    public function __construct(CurrencyConverterStrategy $currencyConverter)
    {
        $this->currencyConverter = $currencyConverter;
    }

    public function processOrder(array $data): array
    {
        if (!preg_match('/^[a-zA-Z\s]+$/', $data['name'])) {
            throw ValidationException::withMessages(['name' => 'Name contains Non-English characters.']);
        }

        // 檢查 name 欄位每個單字的首字母是否大寫
        $words = explode(' ', $data['name']);
        foreach ($words as $word) {
            if ($word !== ucwords($word)) {
                throw ValidationException::withMessages(['name' => 'Name is not Capitalized.']);
            }
        }

        if ($data['price'] > 2000) {
            throw ValidationException::withMessages(['price' => 'Price is over 2000']);
        }

        if (in_array($data['currency'], ['USD', 'TWD'])) {
            if ($data['currency'] === 'USD') {
                $data['price'] = $this->currencyConverter->convert($data['price'], $data['currency']);
                $data['currency'] = 'TWD';
            }
        } else {
            throw ValidationException::withMessages(['currency' => 'Currency format is wrong']);
        }

        return $data;
    }
}
