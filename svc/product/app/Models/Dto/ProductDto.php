<?php

namespace App\Models\Dto;

use Library\Models\Dto\AbstractDto;

/**
 * @class ProductDto
 * @package App\Models\Dto
 */
class ProductDto extends AbstractDto
{
    /**
     * @var int|null $id
     */
    public ?int $id;

    /**
     * @var string|null $code
     */
    public ?string $code;

    /**
     * @var int|null
     */
    public ?int $brand_id;

    /**
     * @var float|null
     */
    public ?float $weight;

    /**
     * @var float|null
     */
    public ?float $volume;

    /**
     * @var int|null
     */
    public ?int $min_lot;

    /**
     * @var string|null
     */
    public ?string $name_en;

    /**
     * @var string|null
     */
    public ?string $name_ru;

    /**
     * @var array|null
     */
    public ?array $photo;
}