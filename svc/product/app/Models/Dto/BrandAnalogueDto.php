<?php declare(strict_types=1);

namespace App\Models\Dto;

use Library\Models\Dto\AbstractDto;

/**
 * @class BrandAnalogueDto
 */
class BrandAnalogueDto extends AbstractDto
{
    /**
     * @var BrandDto|null
     */
    public ?BrandDto $original;

    /**
     * @var BrandDto|null
     */
    public ?BrandDto $analogue;
}