<?php declare(strict_types=1);

namespace App\Models\Eloquent;

use App\Models\Dto\ProductDto;
use Illuminate\Database\Eloquent\Model;
use Library\Models\Eloquent\DataSourceInterface;
use Illuminate\Support\Facades\App;
use Library\Exceptions\InvalidDataException;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @class Product
 * @package App\Models\Eloquent
 */
class Product extends Model implements DataSourceInterface
{
    /**
     * @var string $table
     */
    protected $table = 'product';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param string $code
     * @param string $nameEN
     * @param string $nameRU
     * @param int $brandID
     * @param int $minLot
     * @param float $volume
     * @param float $weight
     * @param array $photo
     *
     * @return bool
     *
     * @throws Throwable
     */
    public function create(
        string $code, string $nameEN, string $nameRU, int $brandID,
        int $minLot, float $volume, float $weight, array $photo = []
    ): bool
    {
        $model = new self();

        $code = strtoupper(
            preg_replace("/[^a-zA-Z\d]/i", '', $code)
        );

        $model->setAttribute('code', $code);
        $model->setAttribute('name_en', $nameEN);
        $model->setAttribute('name_ru', $nameRU);
        $model->setAttribute('brand_id', $brandID);
        $model->setAttribute('min_lot', $minLot);
        $model->setAttribute('volume', $volume);
        $model->setAttribute('weight', $weight);
        $model->setAttribute('photo', json_encode($photo));

        return $model->saveOrFail();
    }

    /**
     * @param array $data
     * @return int
     */
    public function multiInsert(array $data): int
    {
        return DB::table($this->table)->upsert($data, ['code']);
    }

    /**
     * @param int $page
     * @param int|null $perPage
     * @return array
     */
    public function getList(int $page = 0, int $perPage = null): array
    {
        if (!$perPage) {
            $perPage = env('PRODUCT_LIST_PER_PAGE', 50);
        }

        $result = [];

        $models = self::query()->newQuery()->paginate($perPage, ['*'], 'page', $page)->items();

        if (!$models) {
            return [];
        }

        foreach ($models as $model) {
            $result[] = $this->getDto($model);
        }

        return $result;
    }

    /**
     * @param object|array $model
     *
     * @return ProductDto
     */
    private function getDto(object|array $model): ProductDto
    {
        /** @var ProductDto $dto */
        $dto = App::make(ProductDto::class);

        $dto->load($model, ['weight', 'volume']);

        $dto->weight = (float)$model->weight ?? null;
        $dto->volume = (float)$model->volume ?? null;

        return $dto;
    }

    /**
     * @param string $code
     * @param int|null $brandID
     * @return ProductDto
     * @throws InvalidDataException
     */
    public function getByCode(string $code, int $brandID = null): ProductDto
    {
        $model = self::query()
            ->newQuery()
            ->where('code', '=', $code);

        if ($brandID) {
            $model = $model->where('brand_id', '=', $brandID);
        }

        $model = $model->first();

        if (!$model) {
            throw new InvalidDataException('Could not retrieve product for given id');
        }

        return $this->getDto($model);
    }


    /**
     * @param int $id
     * @return void
     */
    public function remove(int $id): void
    {
        self::query()
            ->newQuery()
            ->where('id', '=', $id)
            ->delete();
    }
}