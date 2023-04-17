<?php declare(strict_types=1);

namespace App\Services\Brand;

use App\DataHandlers\Brand\BrandDataHandler;
use App\Models\Dto\BrandDto;
use App\Models\Eloquent\Brand;
use Library\Services\ServiceInterface;
use Illuminate\Support\Facades\App;

/**
 * @class BrandService
 * @package App\Services\Brand
 */
class BrandService implements ServiceInterface, BrandServiceInterface
{
    /**
     * @var BrandDataHandler Источник и обработчик данных (ex.: PostgresSQL, mongoDB)
     */
    public BrandDataHandler $dataHandler;

    public function __construct()
    {
        $this->dataHandler = App::make(BrandDataHandler::class, ['dataSource' => new Brand()]);
    }

    /**
     * {@inheritDoc}
     */
    public function getByID(int $id): BrandDto
    {
        return $this->dataHandler->getByID($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getMappingByID(int $id): array
    {
        return $this->dataHandler->getMappingByID($id);
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
    public function create(string $name, int $countryID): void
    {
        $this->dataHandler->create($name, $countryID);
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
    public function setBrandMapping(string $name, int $originalID, string $source = ''): bool
    {
        return $this->dataHandler->setBrandMapping($name, $originalID, $source);
    }

    /**
     * {@inheritDoc}
     */
    public function getAnalogueList(string $name): array
    {
        return $this->dataHandler->getAnalogueList($name);
    }

    public function getBrandsWithAnalogues(): array
    {
        return $this->dataHandler->getBrandsWithAnalogues();
    }
}