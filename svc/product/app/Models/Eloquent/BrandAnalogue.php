<?php declare(strict_types=1);

namespace App\Models\Eloquent;

use App\Models\Dto\BrandAnalogueDto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Library\Exceptions\InvalidDataException;
use Library\Models\Eloquent\DataSourceInterface;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Throwable;

/**
 * @class BrandAnalogue
 * @package App\Models\Eloquent
 */
class BrandAnalogue extends Model implements DataSourceInterface
{
    /**
     * @var string $table
     */
    protected $table = 'brand_analogue';

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @param string $brandName
     * @return BrandAnalogueDto[]
     * @throws InvalidDataException
     */
    public static function getAnaloguesByBrandName(string $brandName): array
    {
        $result = [];

        $analogueList = self::query()
            ->newQuery()
            ->join('brand', 'brand.id', '=', 'brand_analogue.original_id')
            ->with('originalBrand')
            ->with('analogueBrand')
            ->where('brand.name', '=', $brandName)
            ->getModels();

        if (empty($analogueList)) {
            throw new InvalidDataException('Unable to receive data for given brand name');
        }

        foreach ($analogueList as $analogue) {
            $result []= self::getDto($analogue);
        }

        return $result;
    }

    /**
     * @param object $model
     * @return BrandAnalogueDto
     */
    private static function getDto(object $model): BrandAnalogueDto
    {
        /** @var BrandAnalogueDto $dto */
        $dto = App::make(BrandAnalogueDto::class);

        $dto->analogue = Brand::makeDtoFromModel($model->analogueBrand) ?? null;
        $dto->original = Brand::makeDtoFromModel($model->originalBrand) ?? null;

        $dto->original->alternatives = [];
        $dto->analogue->alternatives = [];

        return $dto;
    }

    /**
     * @return HasOne
     */
    public function originalBrand(): HasOne
    {
        return $this->hasOne('App\Models\Eloquent\Brand', 'id', 'original_id');
    }

    /**
     * @return HasOne
     */
    public function analogueBrand(): HasOne
    {
        return $this->hasOne('App\Models\Eloquent\Brand', 'id', 'analogue_id');
    }
}