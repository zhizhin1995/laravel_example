<?php declare(strict_types=1);

namespace App\Http\Responses\Currency;

use Library\Responses\AbstractResponse;
use App\Models\Dto\Currency\CurrencyDto;

/**
 * @class CurrencyListResponse
 * @package App\Http\Responses\Currency
 */
class CurrencyListResponse extends AbstractResponse
{
    /**
     * @var CurrencyDto[]
     */
    public array $list;

    /**
     * @param CurrencyDto[] $list
     * @return $this
     */
    public function setData(array $list): self
    {
        $this->list = $list;

        return $this;
    }
}