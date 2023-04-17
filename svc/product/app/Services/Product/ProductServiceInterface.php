<?php declare(strict_types=1);

namespace App\Services\Product;

use App\Models\Dto\ProductDto;
use Illuminate\Http\UploadedFile;
use Throwable;

/**
 * @class ProductServiceInterface
 * @package App\Services\Product
 */
interface ProductServiceInterface
{
    /**
     * @return array
     */
    public function getList(): array;

    /**
     * Получить страну по ID
     *
     * @param string $code
     * @param int|null $brandID
     * @return ProductDto
     */
    public function getByCode(string $code, int $brandID = null): ProductDto;

    /**
     * Удалить страну по заданному ID
     *
     * @param int $id
     * @return void
     */
    public function remove(int $id): void;

    /**
     * Добавление новой страны
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
     * @throws Throwable
     *
     * @return bool
     */
    public function create(
        string $code, string $nameEN, string $nameRU, int $brandID,
        int $minLot, float $volume, float $weight, array $photo = []
    ): bool;

    /**
     * Множественное добавление товаров
     *
     * @param array $data
     * @return int
     */
    public function import(array $data): int;

    /**
     * Импорт из файла
     *
     * @param UploadedFile $file
     * @return void
     */
    public function importFromFile(UploadedFile $file): void;
}