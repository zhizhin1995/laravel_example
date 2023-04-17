<?php declare(strict_types=1);

namespace App\Http\Responses\Country;

use Library\Responses\AbstractResponse;

/**
 * @class ProductResponse
 * @package App\Http\Responses\Country;
 */
class CountryResponse extends AbstractResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $flag;
}