<?php declare(strict_types=1);

namespace App\Services\RBAC;

use App\DataHandlers\RBAC\RBACDataHandler;
use Library\Models\Eloquent\ModelHasRoles;
use Library\Services\ServiceInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

/**
 * @class RBACService
 * @package App\Services\Currency
 */
class RBACService implements ServiceInterface, RBACServiceInterface
{
    /**
     * @var RBACDataHandler Источник и обработчик данных (ex.: PostgresSQL, mongoDB)
     */
    public RBACDataHandler $dataHandler;

    public function __construct()
    {
        $this->dataHandler = App::make(RBACDataHandler::class, ['dataSource' => new ModelHasRoles()]);
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function getHasPermission(string $permission): bool
    {
        $roles = $this->dataHandler->getModelRoles(Auth::id(), true);

        return in_array($permission, $this->dataHandler->getPermissions($roles));
    }
}