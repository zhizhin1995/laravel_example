<?php declare(strict_types=1);

namespace App\Services\RBAC;

/**
 * @class RBACServiceInterface
 * @package App\Services\Currency
 */
interface RBACServiceInterface
{
    /**
     * @return bool
     */
    public function getHasPermission(string $permission): bool;
}