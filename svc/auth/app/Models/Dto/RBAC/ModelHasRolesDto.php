<?php declare(strict_types=1);

namespace App\Models\Dto\RBAC;

use App\Models\Dto\AbstractDto;

/**
 * @class ModelHasRolesDto
 * @package App\Models\Dto\RBAC
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
