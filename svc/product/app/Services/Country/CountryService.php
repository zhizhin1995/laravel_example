<?php declare(strict_types=1);

namespace App\Services\Country;

use App\DataHandlers\Country\CountryDataHandler;
use App\Models\Dto\CountryDto;
use App\Models\Eloquent\Country;
use Illuminate\Support\Facades\App;
use Library\Services\ServiceInterface;

/**
 * @class CountryService
 * @package App\Services\Country
 */
class CountryService implements ServiceInterface, CountryServiceInterface
{
    /**
     * @var CountryDataHandler Источник и обработчик данных (ex.: PostgresSQL, mongoDB)
     */
    public CountryDataHandler $dataHandler;

    public function __construct()
    {
        $this->dataHandler = App::make(CountryDataHandler::class, ['dataSource' => new Country()]);
    }

    /**
     * {@inheritDoc}
     */
    public function getList(): array
    {
        return $this->dataHandler->getList();
    }

    /**
     * {@inheritDoc}
     */
    public function getByID(int $id): CountryDto
    {
        return $this->dataHandler->getByID($id);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(int $id): void
    {
        $this->dataHandler->remove($id);
    }

    /**
     * {@inheritDoc}
     */
    public function create(string $name, string $code, string $flag = null): bool
    {
        return $this->dataHandler->create($name, $code, $flag);
    }
}