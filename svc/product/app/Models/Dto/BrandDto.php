<?php declare(strict_types=1);

namespace App\Models\Dto;

use Library\Models\Dto\AbstractDto;

/**
 * @class BrandDto
 * @package App\Models\Dto
 */
class BrandDto extends AbstractDto
{
    /**
     * @var int|null $id
     */
    public ?int $id;

    /**
     * @var string|null $name
     */
    public ?string $name;

    /**
     * @var int|null $countryID
     */
    public ?int $countryID;

    /**
     * @var string|null $countryName
     */
    public ?string $countryName;

    /**
     * @var BrandMappingDto[]|null $alternatives
     */
    public ?array $alternatives = [];
}