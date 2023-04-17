<?php declare(strict_types=1);

namespace App\Models\Eloquent;

use App\Models\Dto\CountryDto;
use Illuminate\Database\Eloquent\Model;
use Library\Exceptions\InvalidDataException;
use Library\Models\Eloquent\DataSourceInterface;
use Illuminate\Support\Facades\App;
use Throwable;

/**
 * @class Country
 * @package App\Models\Eloquent
 */
class Country extends Model implements DataSourceInterface
{
    /**
     * @var string $table
     */
    protected $table = 'country';

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @param string $code
     * @param string $name
     * @param string|null $flag
     * @return bool
     * @throws Throwable
     */
    public function create(string $name, string $code, string $flag = null): bool
    {
        $model = new self();

        $model->setAttribute('code', $code);
        $model->setAttribute('name', $name);

        if ($flag) {
            $model->setAttribute('flag', $flag);
        }

        return $model->saveOrFail();
    }

    /**
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
     * @param object $model
     * @return CountryDto
     */
    private function getDto(object $model): CountryDto
    {
        /** @var CountryDto $dto */
        $dto = App::make(CountryDto::class);

        return $dto->load($model);
    }

    /**
     * @param int $id
     * @return CountryDto
     * @throws InvalidDataException
     */
    public function getByID(int $id): CountryDto
    {
        $model = self::query()
            ->newQuery()
            ->where('id', '=', $id)
            ->first();

        if (!$model) {
            throw new InvalidDataException('Could not retrieve country for given id');
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