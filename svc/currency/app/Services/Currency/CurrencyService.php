<?php declare(strict_types=1);

namespace App\Services\Currency;

use App\DataHandlers\Currency\CurrencyDataHandler;
use Library\Exceptions\InvalidDataException;
use Library\Exceptions\NotPermittedException;
use App\Http\Responses\Currency\CurrencyConvertResponse;
use App\Models\Dto\Currency\CurrencyDto;
use App\Models\Dto\Currency\CurrencyHistoryDto;
use App\Models\Eloquent\Currency;
use Library\Services\ServiceInterface;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Library\RBAC\PermissionValidator;

/**
 * @class CurrencyService
 * @package App\Services\Currency
 */
class CurrencyService implements CurrencyServiceInterface, ServiceInterface
{
    /**
     * @var CurrencyDataHandler Источник и обработчик данных (ex.: PostgresSQL, mongoDB)
     */
    public CurrencyDataHandler $dataHandler;

    public function __construct()
    {
        $this->dataHandler = App::make(CurrencyDataHandler::class, ['dataSource' => new Currency()]);
        $this->rbac = App::make(PermissionValidator::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrentRate(string $code, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): CurrencyHistoryDto
    {
        $data = $this->dataHandler->getCurrentRate($code, $companyName);

        return $data ?? throw new NotFoundHttpException("Could not retrieve data for {$code}");
    }

    /**
     * {@inheritDoc}
     */
    public function getRateByDay(string $code, string $date, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): CurrencyHistoryDto
    {
        $data = $this->dataHandler->getRateByDay($code, $date, $companyName);

        return $data ?? throw new NotFoundHttpException("Could not retrieve data for {$code} and {$date}");
    }

    /**
     * {@inheritDoc}
     * @throws InvalidDataException
     */
    public function setRate(string $code, float $rate, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): bool
    {
        $this->validateRate($rate);

        return $this->dataHandler->setRate($code, $rate, $companyName);
    }

    /**
     * {@inheritDoc}
     * @throws InvalidDataException
     */
    public function create(string $code, float $rate, string $symbol, string $companyName, string $project, bool $isMain): bool
    {
        $this->validateRate($rate);

        return $this->dataHandler->create($code, $rate, $symbol, $companyName, $project, $isMain);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(int $id): bool
    {
        return $this->dataHandler->remove($id);
    }

    /**
     * Валюта из, валюта в, список товаров (id - позиция), потолок 100к
     *
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param array $products
     * @param string|null $date
     * @param string $companyName
     * @return CurrencyConvertResponse
     * @throws NotPermittedException|InvalidDataException
     */
    public function convert(string $currencyFrom, string $currencyTo, array $products, string $date = null, string $companyName = CurrencyDto::DEFAULT_COMPANY_NAME): CurrencyConvertResponse
    {
        if (!$date) {
            $currencyFromDto = $this->getCurrentRate($currencyFrom, $companyName);
            $currencyToDto = $this->getCurrentRate($currencyTo, $companyName);
        } else {
            $currencyFromDto = $this->getRateByDay($currencyFrom, $date, $companyName);
            $currencyToDto = $this->getRateByDay($currencyTo, $date, $companyName);
        }

        foreach ($products as $product) {
            $productVars = get_object_vars($product);

            if (!is_numeric($productVars[key($productVars)])) {
                throw new InvalidDataException('Make sure given data has correct format');
            }

            $key = key($productVars);

            $unratedPrice = (float)$product->$key / $currencyFromDto->rate;
            $product->$key = round($unratedPrice * $currencyToDto->rate);
        }

        /** @var CurrencyConvertResponse $result */
        $result = App::make(CurrencyConvertResponse::class);

        return $result->setData($currencyFrom, $currencyTo, $products);
    }

    /**
     * @param float $rate
     * @return void
     * @throws InvalidDataException
     */
    private function validateRate(float $rate): void
    {
        if ($rate <= 0) {
            throw new InvalidDataException('Rate must be positive and greater than 0');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getList(): array
    {
        return $this->dataHandler->getList();
    }
}