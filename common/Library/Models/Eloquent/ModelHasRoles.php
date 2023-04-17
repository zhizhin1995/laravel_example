<?php declare(strict_types=1);

namespace Library\Models\Eloquent;

use Library\DataHandlers\RBAC\RBACDataHandlerInterface;
use Library\Models\Dto\RBAC\ModelHasRolesDto;
use Library\Models\Dto\RBAC\RolesDto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

/**
 * @class ModelHasRoles
 * @package Library\Models\Eloquent
 *
 * @property RolesDto $role
 */
class ModelHasRoles extends Model implements DataSourceInterface, RBACDataHandlerInterface
{
    /**
     * @var string $table
     */
    protected $table = 'model_has_roles';

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    public function getModelRoles(int $userID): array
    {
        $result = [];

        $models = self::query()->where('model_id', '=', $userID)->getModels();

        if (!$models) {
            return $result;
        }

        foreach ($models as $model) {
            $result[] = self::getDto($model);
        }

        return $result;
    }

    /**
     * Возвращает объект Dto
     *
     * @param object $model
     * @return ModelHasRolesDto
     */
    public static function getDto(object $model): ModelHasRolesDto
    {
        $dto = new ModelHasRolesDto();

        $dto->load($model, ['role']);

        $dto->role = App::make(RolesDto::class)->load($model->role);

        return $dto;
    }

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    /**
     * @param array $roleID
     * @return array|ModelHasRolesDto[]
     */
    public function getPermissions(array $roleID): array
    {
        return RoleHasPermissions::getPermissions($roleID);
    }
}
