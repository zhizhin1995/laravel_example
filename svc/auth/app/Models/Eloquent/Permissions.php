<?php declare(strict_types=1);

namespace App\Models\Eloquent;

use App\Models\DataSourceInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @class Permissions
 * @package App\Models\Eloquent
 */
class Permissions extends Model implements DataSourceInterface
{
    /**
     * @var string $table
     */
    protected $table = 'permissions';
}
