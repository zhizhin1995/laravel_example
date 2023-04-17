<?php declare(strict_types=1);

namespace App\Services\Country;

use App\Models\Dto\CountryDto;

/**
 * @class CountryServiceInterface
 * @package App\Services\Country;
 */
interface CountryServiceInterface
{
    /**
     * @return array
     */
    public function getList(): array;

    /**
     * Получить страну по ID
     *
     * @param int $id
     * @return CountryDto
     */
    public function getByID(int $id): CountryDto;

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
     * @param string $name
     * @param string $code
     * @param string|null $flag
     * @return bool
     */
    public function create(string $name, string $code, string $flag = null): bool;
}