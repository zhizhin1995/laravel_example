<?php declare(strict_types=1);

namespace App\Models\Dto;

use Library\Models\Dto\AbstractDto;

/**
 * @class CountryDto
 * @package App\Models\Dto
 */
class CountryDto extends AbstractDto
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
     * @var string|null $code
     */
    public ?string $code;

    /**
     * @var string|null $flag
     */
    public ?string $flag;
}