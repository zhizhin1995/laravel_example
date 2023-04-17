<?php declare(strict_types=1);

namespace App\DataHandlers\Currency;

use App\DataHandlers\AbstractDataHandler;
use App\Models\Dto\Currency\CurrencyDto;
use App\Models\Dto\Currency\CurrencyHistoryDto;

/**
 * @class CurrencyDataHandler
 * @package App\DataHandlers
 */
class CurrencyDataHandler extends AbstractDataHandler implements CurrencyDataHandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function getCurrentRate(string $code, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): CurrencyHistoryDto|null
    {
        return $this->dataSource->getCurrentRate($code, $companyName);
    }

    /**
     * {@inheritDoc}
     */
    public function getRateByDay(string $code, string $date, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): CurrencyHistoryDto|null
    {
        return $this->dataSource->getRateByDay($code, $date, $companyName);
    }

    /**
     * {@inheritDoc}
     */
    public function setRate(string $code, float $rate, string $companyName): bool
    {
        return $this->dataSource->setRate($code, $rate, $companyName);
    }
    /**
     * {@inheritDoc}
     */
    public function create(string $code, float $rate, string $symbol, string $companyName, string $project, bool $isMain): bool
    {
        return $this->dataSource->create($code, $rate, $symbol, $companyName, $project, $isMain);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(int $id): bool
    {
        return $this->dataSource->remove($id);
    }

    /**
     * @return object|null
     */
    public function getMainCurrency(): object|null
    {
        return $this->dataSource->getCurrentMain();
    }

    /**
     * {@inheritDoc}
     */
    public function getList(): array
    {
        return $this->dataSource->getList();
    }
}