<?php declare(strict_types=1);

namespace App\Models\Dto;

use Library\Models\Dto\AbstractDto;

/**
 * @class BrandMappingDto
 * @package App\Models\Dto
 */
class BrandMappingDto extends AbstractDto
{
    /**
     * @var string $name
     */
    public string $name;

    /**
     * @var string $source
     */
    public string $source;
}