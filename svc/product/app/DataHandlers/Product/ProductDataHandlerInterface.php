<?php declare(strict_types=1);

namespace App\DataHandlers\Product;

use Throwable;
use App\Models\Dto\ProductDto;
use Library\Exceptions\InvalidDataException;

interface ProductDataHandlerInterface
{
    /**
     * Добавление нового товара
     *
     * @param string $code
     * @param string $nameEN
     * @param string $nameRU
     * @param int $brandID
     * @param int $minLot
     * @param float $volume
     * @param float $weight
     * @param array $photo
     *
     * @return bool
     *
     * @throws Throwable
     */
    public function create(
        string $code, string $nameEN, string $nameRU, int $brandID,
        int $minLot, float $volume, float $weight, array $photo = []
    ): bool;

    /**
     * @param array $data
     * @return int
     */
    public function multiInsert(array $data): int;

    /**
     * Получение списка товаров
     *
     * @return array
     */
    public function getList(): array;

    /**
     * Получить товар по ID
     *
     * @param string $code
     * @param int|null $brandID
     * @return ProductDto
     * @throws InvalidDataException
     */
    public function getByCode(string $code, int $brandID = null): ProductDto;


    /**
     * Удалить по ID
     *
     * @param int $id
     * @return void
     */
    public function remove(int $id): void;
}