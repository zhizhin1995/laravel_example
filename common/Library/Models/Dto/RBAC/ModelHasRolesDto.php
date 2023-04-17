<?php declare(strict_types=1);

namespace Library\Models\Dto\RBAC;

use Library\Models\Dto\AbstractDto;

/**
 * @class ModelHasRolesDto
 * @package Library\Models\Dto\RBAC
 */
class ModelHasRolesDto extends AbstractDto
{
    /**
     * @var int|null
     */
    public ?int $role_id;

    /**
     * @var string|null
     */
    public ?string $model_type;

    /**
     * @var int|null
     */
    public ?int $model_id;

    /**
     * @var RolesDto|null
     */
    public ?RolesDto $role;
}
