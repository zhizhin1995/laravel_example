<?php declare(strict_types=1);

namespace Library\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * @class ModelHasRoles
 * @package Library\Models\Eloquent
 */
class Roles extends Model implements DataSourceInterface
{
    /**
     * @var string $table
     */
    protected $table = 'roles';
}
