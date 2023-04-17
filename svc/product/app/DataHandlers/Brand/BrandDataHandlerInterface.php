<?php declare(strict_types=1);

namespace App\DataHandlers\Brand;

use App\Models\Dto\BrandDto;
use App\Models\Dto\BrandMappingDto;

/**
 * @class BrandDataHandlerInterface
 * @package App\DataHandlers\Brand
 */
interface BrandDataHandlerInterface
{
    /**
     * Получаем бренд по его ID
     *
     * @param int $id
     * @return BrandDto
     */
    public function getByID(int $id): BrandDto;

    /**
     * Получаем бренд с соответствиями по его ID
     *
     * @param int $id
     * @return BrandMappingDto[]
     */
    public function getMappingByID(int $id): array;

    /**
     * Получаем список брендов
     *
     * @return array
     */
    public function getList(): array;

    /**
     * Добавление нового бренда
     *
     * @param string $name
     * @param int $countryID
     * @return void
     */
    public function create(string $name, int $countryID): void;

    /**
     * Удаление бренда
     *
     * @param int $id
     * @return void
     */
    public function remove(int $id): void;

    /**
     * Добавление альтернативного обозначения бренда
     *
     * @param string $name
     * @param int $originalID
     * @param string $source
     * @return bool
     */
    public function setBrandMapping(string $name, int $originalID, string $source = ''): bool;

    /**
     * Список аналогов бренда
     *
     * @param string $name
     * @return array
     */
    public function getAnalogueList(string $name): array;

    /**
     * Список брендов и аналогов
     *
     * @return array
     */
    public function getBrandsWithAnalogues(): array;
}