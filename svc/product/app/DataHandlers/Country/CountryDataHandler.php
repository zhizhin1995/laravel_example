<?php declare(strict_types=1);

namespace App\DataHandlers\Country;

use App\Models\Dto\CountryDto;
use Library\DataHandlers\AbstractDataHandler;

/**
 * @class CountryDataHandler
 * @package App\DataHandlers\Country
 */
class CountryDataHandler extends AbstractDataHandler implements CountryDataHandlerInterface
{
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
    public function getByID(int $id): CountryDto
    {
        return $this->dataSource->getByID($id);
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
    public function create(string $name, string $code, string $flag = null): bool
    {
        return $this->dataSource->create($name, $code, $flag);
    }
}
