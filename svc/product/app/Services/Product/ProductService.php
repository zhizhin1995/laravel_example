<?php declare(strict_types=1);

namespace App\Services\Product;

use App\DataHandlers\Product\ProductDataHandler;
use App\Models\Dto\ProductDto;
use App\Models\Eloquent\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Library\Services\ServiceInterface;

/**
 * @class ProductService
 * @package App\Services\Product
 */
class ProductService implements ServiceInterface, ProductServiceInterface
{
    /**
     * @var ProductDataHandler Источник и обработчик данных (ex.: PostgresSQL, mongoDB)
     */
    public ProductDataHandler $dataHandler;

    public function __construct()
    {
        $this->dataHandler = App::make(ProductDataHandler::class, ['dataSource' => new Product()]);
    }

    /**
     * {@inheritDoc}
     */
    public function getList(): array
    {
        return $this->dataHandler->getList();
    }

    /**
     * {@inheritDoc}
     */
    public function getByCode(string $code, int $brandID = null): ProductDto
    {
        return $this->dataHandler->getByCode($code, $brandID);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(int $id): void
    {
        $this->dataHandler->remove($id);
    }

    /**
     * {@inheritDoc}
     */
    public function create(
        string $code, string $nameEN, string $nameRU, int $brandID,
            int $minLot, float $volume, float $weight, array $photo = []
    ): bool
    {
        return $this->dataHandler->create(
            $code, $nameEN, $nameRU, $brandID,  $minLot, $volume, $weight,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function import(array $data): int
    {
        try {
            return $this->dataHandler->multiInsert($data);
        } catch (\Throwable $ex) {
            var_dump($ex->getMessage());die;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function importFromFile(UploadedFile $file): void
    {
        $data = file($file->getFilename());
    }
}