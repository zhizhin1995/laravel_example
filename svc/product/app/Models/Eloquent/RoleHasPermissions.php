<?php declare(strict_types=1);

namespace App\Models\Eloquent;

use App\Models\DataSourceInterface;
use App\Models\Dto\RBAC\PermissionsDto;
use App\Models\Dto\RBAC\RoleHasPermissionsDto;
use App\Models\Dto\RBAC\RolesDto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

/**
 * @class RoleHasPermissions
 * @package App\Models\Eloquent
 */
class RoleHasPermissions extends Model implements DataSourceInterface
{
    /**
     * @var string $table
     */
    protected $table = 'role_has_permissions';

    /**
     * Получаем список разрешений
     *
     * @param array $roleID
     * @return array
     */
    public static function getPermissions(array $roleID): array
    {
        $result = [];

        if (empty($roleID)) {
            return $result;
        }

        $models = self::query()->newQuery()->whereIn('role_id', $roleID)->distinct()->getModels() ?? [];

        if (empty($models)) {
            return $result;
        }

        foreach ($models as $model) {
            $result[] = RoleHasPermissions::getDto($model);
        }

        return $result;
    }

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    /**
     * @return BelongsTo
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permissions::class, 'permission_id');
    }

    /**
     * Возвращает объект Dto
     *
     * @param object $model
     * @return RoleHasPermissionsDto
     */
    public static function getDto(object $model): RoleHasPermissionsDto
    {
        $dto = new RoleHasPermissionsDto();

        $dto->role = App::make(RolesDto::class)->load($model->role);
        $dto->permission = App::make(PermissionsDto::class)->load($model->permission);

        return $dto;
    }
}
