<?php declare(strict_types=1);

namespace App\DataHandlers\Currency;

use App\Models\Dto\Currency\CurrencyDto;
use App\Models\Dto\Currency\CurrencyHistoryDto;

/**
 * @class CurrencyServiceInterface
 * @package App\DataHandlers\Currency;
 */
interface CurrencyDataHandlerInterface
{
    /**
     * Получаем курс за текущий день
     *
     * @param string $code Код валюты (пример: USD)
     * @param string $companyName Название компании (в случае отдельного курса валюты)
     * @return CurrencyHistoryDto|null
     */
    public function getCurrentRate(string $code, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): CurrencyHistoryDto|null;

    /**
     * Получаем курс валюты за определенную дату
     *
     * @param string $code Код валюты (пример: USD)
     * @param string $date Дата, за которую нужно выдать курс валюты
     * @param string $companyName Название компании (в случае отдельного курса валюты)
     * @return CurrencyHistoryDto|null
     */
    public function getRateByDay(string $code, string $date, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): CurrencyHistoryDto|null;

    /**
     * Устанавливаем курс валюты для определенной компании
     *
     * @param string $code
     * @param string $companyName
     * @param float $rate
     * @return bool
     */
    public function setRate(string $code, float $rate, string $companyName): bool;

    /**
     * Устанавливаем курс валюты для определенной компании
     *
     * @param string $code
     * @param float $rate
     * @param string $symbol
     * @param string $companyName
     * @param string $project
     * @param bool $isMain
     * @return bool
     */
    public function create(string $code, float $rate, string $symbol, string $companyName, string $project, bool $isMain): bool;

    /**
     * Удаление валюты
     *
     * @param int $id
     *
     * @return bool
     */
    public function remove(int $id): bool;

    /**
     * Получение списка доступных валют
     *
     * @return array
     */
    public function getList(): array;
}