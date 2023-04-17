<?php declare(strict_types=1);

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Library\Models\Eloquent\DataSourceInterface;

/**
 * @class ProductAnalogue
 * @package App\Models\Eloquent
 */
class ProductAnalogue extends Model implements DataSourceInterface
{
    /**
     * @var string $table
     */
    protected $table = 'product_analogue';
}