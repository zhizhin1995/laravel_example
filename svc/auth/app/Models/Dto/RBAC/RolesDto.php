<?php declare(strict_types=1);

namespace App\Models\Dto\RBAC;

use App\Models\Dto\AbstractDto;

/**
 * @class RolesDto
 * @package App\Models\Dto\RBAC
 */
class RolesDto extends AbstractDto
{
    /**
     * @var int|null
     */
    public ?int $id;

    /**
     * @var string|null
     */
    public ?string $name;

    /**
     * @var string|null
     */
    public ?string $guard_name;

    /**
     * @var int|null
     */
    public ?int $service_id;
}
