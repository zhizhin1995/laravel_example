<?php declare(strict_types=1);

namespace App\Models\Eloquent;

use App\Models\Dto\BrandDto;
use App\Models\Dto\BrandMappingDto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\App;
use Library\Exceptions\InvalidDataException;
use Library\Models\Eloquent\DataSourceInterface;
use Throwable;

/**
 * @class Brand
 * @package App\Models\Eloquent
 */
class Brand extends Model implements DataSourceInterface
{
    /**
     * @var string $table
     */
    protected $table = 'brand';

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * Добавление нового бренда
     *
     * @param string $name
     * @param int $countryID
     * @return bool
     * @throws Throwable
     */
    public function create(string $name, int $countryID): bool
    {
        $model = new self();

        $model->setAttribute('name', $name);
        $model->setAttribute('country_id', $countryID);

        return $model->saveOrFail();
    }

    /**
     * Получение бренда по ID
     *
     * @param int $id
     * @param bool $withAlternatives
     * @return BrandDto
     * @throws InvalidDataException
     */
    public function getByID(int $id, bool $withAlternatives = true): BrandDto
    {
        $model = self::query()
            ->newQuery()
            ->where('id', '=', $id)
            ->with('country')
            ->get()
            ->first();

        if (!$model) {
            throw new InvalidDataException('Could not retrieve brand for given id');
        }

        $dto = $this->getDto($model);

        if ($withAlternatives) {
            $dto->alternatives = $this->getMappingByID($id);
        }

        return $dto;
    }

    /**
     * Получение подобных брендов по ID
     *
     * @param int $id
     * @return BrandMappingDto[]
     */
    public function getMappingByID(int $id): array
    {
        /** @var BrandNameMapping $model */
        $model = App::make(BrandNameMapping::class);

        return $model->getAnalogues($id);
    }

    /**
     * @param object|array $model
     * @return BrandDto
     */
    private function getDto(object|array $model): BrandDto
    {
        /** @var BrandDto $dto */
        $dto = App::make(BrandDto::class);

        $dto->load($model);

        $dto->countryName = $model->country->name;
        $dto->countryID = $model->country->id;

        return $dto;
    }

    /**
     * @param Brand $model
     * @return BrandDto
     */
    public static function makeDtoFromModel(self $model): BrandDto
    {
        return $model->getDto($model);
    }

    /**
     * Получение списка брендов
     *
     * @return array
     */
    public function getList(): array
    {
        $result = [];

        $models = self::query()->newQuery()->getModels();

        if (!$models) {
            return [];
        }

        foreach ($models as $model) {
            $result[] = $this->getDto($model);
        }

        return $result;
    }

    /**
     * Добавление нового бренда
     *
     * @param string $name
     * @param int $countryID
     * @return void
     * @throws Throwable
     */
    public function createBrand(string $name, int $countryID): void
    {
        /** @var Brand $model */
        $model = App::make(self::class);

        $model->setAttribute('name', strtoupper($name));
        $model->setAttribute('country_id', $countryID);

        $model->saveOrFail();
    }

    /**
     * Удаление по ID
     *
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

    /**
     * Добавление нового бренда
     *
     * @param string $name
     * @param int $originalID
     * @param string $source
     * @return bool
     * @throws Throwable
     */
    public function setBrandMapping(string $name, int $originalID, string $source = ''): bool
    {
        /** @var BrandNameMapping $model */
        $model = App::make(BrandNameMapping::class);

        return $model->create($name, $originalID, $source);
    }

    /**
     * @param string $name
     * @return array
     * @throws InvalidDataException
     */
    public function getAnalogueList(string $name): array
    {
        return BrandAnalogue::getAnaloguesByBrandName($name);
    }

    /**
     * @return array
     */
    public function getBrandsWithAnalogues(): array
    {
        $result = self::with('analogues')->getModels();

        foreach ($result as $model) {
            var_dump($model->analogues);
        }

        die;

        return self::with('analogues')->getModels();
    }

    /**
     * Аналоги брендов
     *
     * @return HasMany
     */
    public function analogues(): HasMany
    {
        return $this->hasMany('App\Models\Eloquent\BrandNameMapping', 'id', 'original_brand_id');
    }

    public function mapping(): HasMany
    {
        return $this->hasMany('');
    }

    /**
     * Страна
     *
     * @return HasOne
     */
    public function country(): HasOne
    {
        return $this->hasOne('App\Models\Eloquent\Country', 'id', 'country_id');
    }
}