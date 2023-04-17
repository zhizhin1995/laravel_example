<?php declare(strict_types=1);

namespace App\Http\Responses\Currency;

use App\Models\Dto\Currency\CurrencyHistoryDto;
use Library\Responses\AbstractResponse;

/**
 * @class CurrencyRateResponse
 * @package App\Http\Responses\Currency
 */
class CurrencyRateResponse extends AbstractResponse
{
    /**
     * @var string
     */
    public string $symbol;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var float
     */
    public float $rate;

    /**
     * @var string
     */
    public string $date;

    /**
     * @var string
     */
    public string $companyName;

    /**
     * @param CurrencyHistoryDto $dto
     * @return self
     */
    public function setData(CurrencyHistoryDto $dto): self
    {
        $this->symbol = $dto->currency->symbol;
        $this->date = date('Y-m-d', strtotime($dto->created_at));
        $this->rate = $dto->rate;
        $this->code = $dto->currency->code;
        $this->companyName = $dto->currency->company;

        return $this;
    }
}