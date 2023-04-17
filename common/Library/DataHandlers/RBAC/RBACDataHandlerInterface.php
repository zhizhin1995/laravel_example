<?php declare(strict_types=1);

namespace Library\DataHandlers\RBAC;

use Library\Models\Dto\RBAC\ModelHasRolesDto;

/**
 * @class RBACDataHandlerInterface
 * @package Library\DataHandlers\RBAC
 */
interface RBACDataHandlerInterface
{
    /**
     * @const Тип моделей пользователи
     */
    const MODEL_TYPE_USERS = 'users';

    /**
     * @const Тип моделей сервисы
     */
    const MODEL_SERVICES = 'services';

    /**
     * Получение ролей модели (пользователей или сервисов)
     *
     * Формат - serviceID_roleID
     *
     * @param int $userID
     *
     * @return ModelHasRolesDto[]
     */
    public function getModelRoles(int $userID): array;

    /**
     * Получение разрешений роли
     *
     * Формат - serviceID_roleID
     *
     * @param array $roleID
     *
     * @return ModelHasRolesDto[]
     */
    public function getPermissions(array $roleID): array;
}