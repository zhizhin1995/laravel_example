<?php declare(strict_types=1);

namespace App\Models\Dto\RBAC;

use App\Models\Dto\AbstractDto;

/**
 * @class RoleHasPermissionsDto
 * @package App\Models\Dto\RBAC
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
