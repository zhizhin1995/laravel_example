<?php declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @class CreatesAuthAssignment
 * @package Library\Tests\Traits
 */
trait CreatesAuthAssignment
{
    /**
     * Присваивает роль тестовому пользователю
     *
     * @param object $user
     * @param int $roleID
     * @return void
     */
    public function createAssignment(object $user, int $roleID = 1): void
    {
        try {
            DB::insert('insert into model_has_roles (role_id, model_type, model_id) values (?, ?, ?)', [
                $roleID,
                'users',
                $user->id,
            ]);
        } catch (Throwable) {
            return;
        }
    }
}
