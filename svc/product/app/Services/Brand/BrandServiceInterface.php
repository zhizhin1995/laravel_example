<?php declare(strict_types=1);

namespace App\Services\Brand;

use App\DataHandlers\Brand\BrandDataHandlerInterface;

/**
 * @class BrandDataHandlerInterface
 * @package App\Services\Brand
 */
interface BrandServiceInterface extends BrandDataHandlerInterface
{
    /**
     * Добавление нового бренда
     *
     * @param string $name
     * @param int $countryID
     * @return void
     */
    public function create(string $name, int $countryID): void;

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
     * Аналоги бренда по именам
     *
     * @param string $name
     * @return array
     */
    public function getAnalogueList(string $name): array;

    /**
     * Возвращает все бренды и аналоги
     *
     * @return array
     */
    public function getBrandsWithAnalogues(): array;
}