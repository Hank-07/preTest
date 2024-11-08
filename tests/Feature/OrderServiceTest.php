<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\OrderService;
use App\Strategies\CurrencyConverterStrategy;
use Mockery;
use Illuminate\Validation\ValidationException;

class OrderServiceTest extends TestCase
{
    protected $currencyConverterMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->currencyConverterMock = Mockery::mock(CurrencyConverterStrategy::class);
    }

    public function testNameContainsNonEnglishCharacters()
    {
        $orderService = new OrderService($this->currencyConverterMock);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Name contains Non-English characters.');

        // 嘗試處理包含非英文字元的名字
        $data = [
            'name' => 'Melody@Holiday Inn',
            'price' => 1000,
            'currency' => 'TWD'
        ];

        $orderService->processOrder($data);
    }

    public function testNameNotCapitalized()
    {
        $orderService = new OrderService($this->currencyConverterMock);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Name is not Capitalized.');

        // 嘗試處理名字中首字母未大寫的情況
        $data = [
            'name' => 'melody Holiday Inn',
            'price' => 1000,
            'currency' => 'TWD'
        ];

        $orderService->processOrder($data);
    }

    public function testPriceGreaterThan2000()
    {
        $orderService = new OrderService($this->currencyConverterMock);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Price is over 2000');

        // 嘗試處理價格超過 2000 的情況
        $data = [
            'name' => 'Melody Holiday Inn',
            'price' => 3000,
            'currency' => 'TWD'
        ];

        $orderService->processOrder($data);
    }

    public function testCurrencyFormatWrong()
    {
        $orderService = new OrderService($this->currencyConverterMock);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Currency format is wrong');

        // 嘗試處理錯誤的貨幣格式
        $data = [
            'name' => 'Melody Holiday Inn',
            'price' => 1000,
            'currency' => 'EUR'
        ];

        $orderService->processOrder($data);
    }

    public function testConvertUSDToTWD()
    {
        // 假設我們 mock 的轉換器會將 1 USD 轉換成 30 TWD
        $this->currencyConverterMock
            ->shouldReceive('convert')
            ->once()
            ->with(1000, 'USD')
            ->andReturn(30000);

        $orderService = new OrderService($this->currencyConverterMock);

        $data = [
            'name' => 'Melody Holiday Inn',
            'price' => 1000,
            'currency' => 'USD'
        ];

        $result = $orderService->processOrder($data);

        $this->assertEquals(30000, $result['price']);
        $this->assertEquals('TWD', $result['currency']);
    }
}
