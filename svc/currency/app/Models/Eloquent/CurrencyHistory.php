<?php declare(strict_types=1);

namespace App\Models\Eloquent;

use Library\Exceptions\InvalidDataException;
use App\Models\DataSourceInterface;
use App\Models\Dto\Currency\CurrencyDto;
use App\Models\Dto\Currency\CurrencyHistoryDto;
use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * @class CurrencyHistory
 * @package App\Models\Eloquent
 */
class CurrencyHistory extends Model implements DataSourceInterface
{
    /**
     * @var string $table
     */
    protected $table = 'currency_history';

    /**
     * @var string[]
     */
    protected $casts = [
        'rate' => 'float',
    ];

    /**
     * Устанавливает курс на текущий день
     *
     * @param string $code
     * @param float $rate
     * @param string $companyName
     * @return bool
     * @throws InvalidDataException
     * @throws Throwable
     */
    public function setRate(string $code, float $rate, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): bool
    {
        $currency = Currency::query()
            ->where('code', '=', $code)
            ->where('company', '=', $companyName)
            ->first();

        if (!$currency) {
            throw new InvalidDataException("Could not retrieve data for '{$code}' currency");
        }

        if (!$model = $this->getExistingRate($currency->id)) {
            $model = new self();
            $model->setAttribute('currency_id', $currency->id);
        }

        $model->setAttribute('rate', $rate);

        return $model->saveOrFail();
    }

    /**
     * @param int $currencyID
     * @return Model|null
     */
    private function getExistingRate(int $currencyID): Model|null
    {
        $date = date('Y-m-d', time());

        return self::query()->where('currency_id', '=', $currencyID)
            ->where('created_at', '=', $date)
            ->first();
    }

    /**
     * Возвращает объект Dto
     *
     * @param object $model
     * @return CurrencyHistoryDto
     */
    public static function getDto(object $model): CurrencyHistoryDto
    {
        $dto = new CurrencyHistoryDto();
        $dto = $dto->load($model);

        if (!$dto->currency) {
            $dto->currency = new CurrencyDto();
        }

        $dto->currency->code = $model->code ?? null;
        $dto->currency->symbol = $model->symbol ?? null;
        $dto->currency->company = $model->company ?? null;

        return $dto;
    }
}
