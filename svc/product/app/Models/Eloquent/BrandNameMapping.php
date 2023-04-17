<?php declare(strict_types=1);

namespace App\Models\Eloquent;

use App\Models\Dto\BrandMappingDto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Library\Models\Eloquent\DataSourceInterface;
use Throwable;

/**
 * @class BrandNameMapping
 * @package App\Models\Eloquent
 */
class BrandNameMapping extends Model implements DataSourceInterface
{
    /**
     * @var string $table
     */
    protected $table = 'brand_name_mapping';

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @param int $brandID
     * @return array
     */
    public function getAnalogues(int $brandID): array
    {
        $result = [];

        $models = self::query()
            ->newQuery()
            ->where('original_brand_id', '=', $brandID)
            ->getModels();

        foreach ($models as $model) {
            $result[] = $this->getDto($model);
        }

        return $result;
    }

    /**
     * @param object $model
     * @return BrandMappingDto
     */
    private function getDto(object $model): BrandMappingDto
    {
        /** @var BrandMappingDto $dto*/
        $dto = App::make(BrandMappingDto::class);

        $dto->name = $model->mapping_name;
        $dto->source = $model->source;

        return $dto;
    }

    /**
     * @param string $name
     * @param int $originalID
     * @param string $source
     * @return bool
     * @throws Throwable
     */
    public function create(string $name, int $originalID, string $source = ''): bool
    {
        $model = new self();

        $model->original_brand_id = $originalID;
        $model->mapping_name = $name;

        if (!empty($source)) {
            $model->source = $source;
        }

        return $model->saveOrFail();
    }
}