<?php declare(strict_types=1);

namespace App\DataHandlers\Brand;

use App\Models\Dto\BrandDto;
use Library\DataHandlers\AbstractDataHandler;

/**
 * @class BrandDataHandler
 * @package App\DataHandlers\Brand
 */
class BrandDataHandler extends AbstractDataHandler implements BrandDataHandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function getByID(int $id): BrandDto
    {
        /** @var BrandDto $result */
        $result = $this->dataSource->getByID($id);

        unset($result->alternatives);

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getMappingByID(int $id): array
    {
        return $this->dataSource->getMappingByID($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getList(): array
    {
        return $this->dataSource->getList();
    }

    /**
     * {@inheritDoc}
     */
    public function create(string $name, int $countryID): void
    {
        $this->dataSource->createBrand($name, $countryID);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(int $id): void
    {
        $this->dataSource->remove($id);
    }

    /**
     * {@inheritDoc}
     */
    public function setBrandMapping(string $name, int $originalID, string $source = ''): bool
    {
        return $this->dataSource->setBrandMapping($name, $originalID, $source);
    }

    /**
     * {@inheritDoc}
     */
    public function getAnalogueList(string $name): array
    {
        return $this->dataSource->getAnalogueList($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getBrandsWithAnalogues(): array
    {
        return $this->dataSource->getBrandsWithAnalogues();
    }
}