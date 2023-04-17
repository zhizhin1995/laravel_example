<?php declare(strict_types=1);

namespace Library\Models\Dto\RBAC;

use Library\Models\Dto\AbstractDto;

/**
 * @class RoleHasPermissionsDto
 * @package Library\Models\Dto\RBAC
 */
class RoleHasPermissionsDto extends AbstractDto
{
    /**
     * @var RolesDto|null
     */
    public ?RolesDto $role;

    /**
     * @var PermissionsDto|null
     */
    public ?PermissionsDto $permission;
}
