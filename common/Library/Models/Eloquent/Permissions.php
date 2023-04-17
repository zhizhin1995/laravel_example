<?php declare(strict_types=1);

namespace Library\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * @class Permissions
 * @package Library\Models\Eloquent
 */
class Permissions extends Model implements DataSourceInterface
{
    /**
     * @var string $table
     */
    protected $table = 'permissions';
}
