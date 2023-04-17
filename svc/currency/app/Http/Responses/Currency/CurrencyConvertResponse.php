<?php declare(strict_types=1);

namespace App\Http\Responses\Currency;

use Library\Responses\AbstractResponse;

/**
 * @class CurrencyConvertResponse
 * @package App\Http\Responses\Currency
 */
class CurrencyConvertResponse extends AbstractResponse
{
    /**
     * @var string
     */
    public string $currencyFrom;

    /**
     * @var string
     */
    public string $currencyTo;

    /**
     * @var object[]
     */
    public array $products;

    /**
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param array $products
     * @return $this
     */
    public function setData(string $currencyFrom, string $currencyTo, array $products): self
    {
        $this->currencyTo = $currencyTo;
        $this->currencyFrom = $currencyFrom;
        $this->products = $products;

        return $this;
    }
}