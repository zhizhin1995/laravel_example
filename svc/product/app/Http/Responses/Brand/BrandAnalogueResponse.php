<?php

namespace App\Http\Responses\Brand;

use Library\Responses\AbstractResponse;

/**
 * @class BrandAnalogueResponse
 * @package App\Http\Responses\Brand;
 */
class BrandAnalogueResponse extends AbstractResponse
{
    /**
     * @var array
     */
    public array $list = [];

    /**
     * @param array $data
     * @return self
     */
    public function setData(array $data): self
    {
        return $this->load($data);
    }
}