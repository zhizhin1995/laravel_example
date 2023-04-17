<?php declare(strict_types=1);

namespace App\Services\Currency;

use Library\Exceptions\NotPermittedException;
use App\Models\Dto\Currency\CurrencyDto;
use App\Models\Dto\Currency\CurrencyHistoryDto;

/**
 * @class CurrencyServiceInterface
 * @package App\Services\Currency
 */
interface CurrencyServiceInterface
{
    /**
     * @const string Дефолтная компания
     */
    const DEFAULT_COMPANY_NAME = 'common';

    /**
     * Получение текущего курса валюты
     *
     * @param string $code
     * @param string $companyName
     *
     * @throws NotPermittedException
     *
     * @return CurrencyHistoryDto
     */
    public function getCurrentRate(string $code, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): CurrencyHistoryDto;

    /**
     * Получение курса валюты по дате
     *
     * @param string $code
     * @param string $date
     * @param string $companyName
     *
     * @return CurrencyHistoryDto
     *
     * @throws NotPermittedException
     */
    public function getRateByDay(string $code, string $date, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): CurrencyHistoryDto;

    /**
     * Устанавливаем курс валюты для определенной компании
     *
     * @param string $code
     * @param string $companyName
     * @param float $rate
     *
     * @throws NotPermittedException
     *
     * @return bool
     */
    public function setRate(string $code, float $rate, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): bool;

    /**
     * Добавление курса валюты и установление курса
     *
     * @param string $code
     * @param float $rate
     * @param string $symbol
     * @param string $companyName
     * @param string $project
     * @param bool $isMain
     *
     * @throws NotPermittedException
     *
     * @return bool
     */
    public function create(string $code, float $rate, string $symbol, string $companyName, string $project, bool $isMain): bool;

    /**
     * Удаление валюты
     *
     * @param int $id
     *
     * @throws NotPermittedException
     *
     * @return bool
     */
    public function remove(int $id): bool;

    /**
     * Получение списка доступных валют
     *
     * @return array
     *
     * @throws NotPermittedException
     */
    public function getList(): array;
}