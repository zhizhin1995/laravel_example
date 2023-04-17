<?php declare(strict_types=1);

namespace App\Models\Dto;

use App\Traits\PropertyLoadTrait;

/**
 * @class AbstractDto
 * @package App\Models\Dto
 */
abstract class AbstractDto
{
    use PropertyLoadTrait;
}