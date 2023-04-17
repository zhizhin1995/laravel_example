<?php declare(strict_types=1);

namespace App\Models\Eloquent;

use App\DataHandlers\Currency\CurrencyDataHandlerInterface;
use Library\Exceptions\InvalidDataException;
use Library\Exceptions\NotUniqueDataException;
use App\Models\DataSourceInterface;
use App\Models\Dto\Currency\CurrencyDto;
use App\Models\Dto\Currency\CurrencyHistoryDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * @class Currency
 * @package App\Models\Eloquent
 */
class Currency extends Model implements DataSourceInterface, CurrencyDataHandlerInterface
{
    /**
     * @var string $table
     */
    protected $table = 'currency';

    /**
     * Получение текущего курса
     *
     * @param string $code
     * @param string $companyName
     * @return CurrencyHistoryDto|null
     */
    public function getCurrentRate(string $code, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): CurrencyHistoryDto|null
    {
        $model = $this->getQuery($code, $companyName)->orderBy('currency_history.created_at', 'DESC')->first() ?? null;

        if (!$model) {
            return null;
        }

        return $this->getDto($model);
    }

    /**
     * Получение курса за определенную дату
     *
     * @param string $code
     * @param string $date
     * @param string $companyName
     * @return CurrencyHistoryDto|null
     */
    public function getRateByDay(string $code, string $date, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): CurrencyHistoryDto|null
    {
        $model = $this->getQuery($code, $companyName)
            ->where('currency_history.created_at', '=', $date)
            ->first();

        if (!$model) {
            return $this->getCurrentRate($code, $companyName);
        }

        return $this->getDto($model);
    }

    /**
     * Устанавливаем курс для валюты
     *
     * @param string $code
     * @param string $companyName
     * @param float $rate
     * @return bool
     * @throws Throwable
     *
     */
    public function setRate(string $code, float $rate, string $companyName): bool
    {
        $model = new CurrencyHistory();

        return $model->setRate($code, $rate, $companyName);
    }

    /**
     * Формирует базовый запрос
     *
     * @param string $code * @param string $company
     * @param string $company
     * @return Builder
     */
    private function getQuery(string $code, string $company): Builder
    {
        return CurrencyHistory::query()
            ->select(['currency_history.rate', 'c.id', 'c.code', 'c.symbol', 'currency_history.created_at', 'c.company'])
            ->leftJoin('currency as c', 'currency_history.currency_id', '=', 'c.id')
            ->where('code', '=', $code)
            ->where('company', '=', $company);
    }

    /**
     * Формирует Dto для отдачи в ответ
     *
     * @param object $model
     * @return CurrencyHistoryDto
     */
    private function getDto(object $model): CurrencyHistoryDto
    {
        return CurrencyHistory::getDto($model);
    }

    /**
     * Добавление новой валюты
     *
     * @param string $code
     * @param float $rate
     * @param string $symbol
     * @param string $companyName
     * @param string $project
     * @param bool $isMain
     * @return bool
     * @throws NotUniqueDataException
     * @throws Throwable
     * @throws InvalidDataException
     */
    public function create(string $code, float $rate, string $symbol, string $companyName, string $project, bool $isMain): bool
    {
        $this->isUniqueCurrencyValidation($code, $companyName, $project);

        $model = new self();

        $model->setRawAttributes([
            'code' => $code,
            'company' => $companyName,
            'symbol' => $symbol,
            'project' => $project,
            'is_main' => $isMain,
        ]);

        /** @var self $current */
        if ($model->saveOrFail() && $isMain && $current = $this->getCurrentMain()) {
            $current->setAttribute('is_main', 0);

            $current->save();
        }

        $currencyHistory = new CurrencyHistory();

        return $currencyHistory->setRate($code, $rate, $companyName);
    }

    /**
     * @return object|null
     */
    public function getCurrentMain(): object|null
    {
        return self::newQuery()->where('is_main', '=', 1)->first();
    }

    /**
     * Проверяет существование записи в БД
     *
     * @param string $code
     * @param string $company
     * @param string $project
     * @return void
     * @throws NotUniqueDataException
     */
    private function isUniqueCurrencyValidation(string $code, string $company, string $project): void
    {
        try {
            self::query()
                ->where('code', '=', $code)
                ->where('company', '=', $company)
                ->where('project', '=', $project)
                ->firstOrFail();
        } catch (Throwable) {
            return;
        }

        throw new NotUniqueDataException("Currency with given data already exists ({$code}, {$company}, {$project})");
    }

    /**
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool
    {
        return (bool)self::query()->where('id', '=', $id)->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function getList(): array
    {
        $result = [];

        $currencies = self::query()->get();


        if (empty($currencies)) {
            throw new InvalidDataException('No available currencies found');
        }

        foreach ($currencies as $currency) {
            $dto = new CurrencyDto();

            $result[]= $dto->load($currency);
        }

        return $result;
    }
}
