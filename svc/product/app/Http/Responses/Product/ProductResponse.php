<?php declare(strict_types=1);

namespace App\Http\Responses\Product;

use Library\Responses\AbstractResponse;

/**
 * @class ProductResponse
 * @package App\Http\Responses\Product;
 */
class ProductResponse extends AbstractResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $brand_id;

    /**
     * @var float
     */
    public float $weight;

    /**
     * @var float
     */
    public float $volume;

    /**
     * @var int
     */
    public int $min_lot;

    /**
     * @var string
     */
    public string $name_en;

    /**
     * @var string
     */
    public string $name_ru;

    /**
     * @var array
     */
    public array $photo = [];
}