<?php declare(strict_types=1);

namespace App\DataHandlers\Product;

use App\Models\Dto\ProductDto;
use Library\DataHandlers\AbstractDataHandler;
use Library\Exceptions\InvalidDataException;
use Throwable;

/**
 * @class ProductDataHandler
 * @package App\DataHandlers\Product;
 */
class ProductDataHandler extends AbstractDataHandler implements ProductDataHandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(
        string $code, string $nameEN, string $nameRU, int $brandID,
            int $minLot, float $volume, float $weight, array $photo = []
    ): bool
    {
        return $this->dataSource->create(
            $code, $nameEN, $nameRU, $brandID, $minLot, $volume, $weight, $photo
        );
    }

    /**
     * {@inheritDoc}
     */
    public function multiInsert(array $data): int
    {
        return $this->dataSource->multiInsert($data);
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
    public function getByCode(string $code, int $countryID = null): ProductDto
    {
        return $this->dataSource->getByCode($code, $countryID);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(int $id): void
    {
         $this->dataSource->remove($id);
    }
}