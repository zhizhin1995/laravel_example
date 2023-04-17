<?php

namespace App\Http\Responses\Brand;

use Library\Responses\AbstractResponse;

/**
 * @class App\Http\Responses\Brand;
 * @package App\Http\Responses\Brand;
 */
class BrandResponse extends AbstractResponse
{
    /**
     * @var int $id
     */
    public int $id;

    /**
     * @var string $name
     */
    public string $name;

    /**
     * @var int $countryID
     */
    public int $countryID;

    /**
     * @var string $countryName
     */
    public string $countryName;

    /**
     * @param object|array $data
     * @return self
     */
    public function setData(object|array $data): self
    {
        return $this->load($data);
    }
}