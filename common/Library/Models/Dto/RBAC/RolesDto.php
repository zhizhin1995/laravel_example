<?php declare(strict_types=1);

namespace Library\Models\Dto\RBAC;

use Library\Models\Dto\AbstractDto;

/**
 * @class RolesDto
 * @package Library\Models\Dto\RBAC
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
