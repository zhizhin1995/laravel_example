<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Jobs\ProductImportJob;
use App\Models\Dto\ProductDto;
use App\Queues\Product\Import\ImportJob;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Library\Exceptions\AuthException;
use Library\Exceptions\InvalidDataException;
use Tests\TestCase;
use stdClass;
use Throwable;
use Kunnu\RabbitMQ\RabbitMQMessage;
use Kunnu\RabbitMQ\RabbitMQManager;
use PhpAmqpLib\Message\AMQPMessage;
use Kunnu\RabbitMQ\ConnectionConfig;
use Kunnu\RabbitMQ\RabbitMQExchange;

/**
 * @class ProductServiceTest
 * @package Tests\Unit
 */
class ProductServiceTest extends TestCase
{
    /**
     * @var ProductService $service
     */
    private ProductService $service;

    /**
     * @return void
     * @throws AuthException|AuthException
     */
    public function setUp(): void
    {
        $this->service = $this->app->make(ProductService::class);

        $this->token = $this->createToken($this->app);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testGetList(): void
    {
        $result = $this->service->getList();

        foreach ($result as $model) {
            $this->assertProduct($model);
        }
    }

    /**
     * @param ProductDto $dto
     * @return void
     */
    private function assertProduct(ProductDto $dto): void
    {
        $this->assertIsNumeric($dto->id);
        $this->assertIsNumeric($dto->volume);
        $this->assertIsNumeric($dto->weight);
        $this->assertIsNumeric($dto->brand_id);

        $this->assertNotNull($dto->id);
        $this->assertNotNull($dto->volume);
        $this->assertNotNull($dto->weight);
        $this->assertNotNull($dto->brand_id);

        $this->assertNotEmpty($dto->name_en);
        $this->assertNotEmpty($dto->name_ru);
        $this->assertNotEmpty($dto->code);
    }

    /**
     * @return void
     */
    public function testGetById(): void
    {
        $this->assertProduct($this->service->getByCode('14545TOYO453OF'));

        try {
            $this->service->getByCode('123123123');
        } catch (InvalidDataException $ex) {
            $this->assertEquals('Could not retrieve product for given id', $ex->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testDeleteAndCreate(): void
    {
        //пытаемся создать товар с мусором в артикуле
        $this->assertTrue(
            $this->service->create(
            '666F$$$$Aw453Of@#', 'Oil filter',
            'Масляный фильтр', 1, 1, 1.23, 2.34
            )
        );

        $product = $this->getRawData('666FAW453OF');
        $this->assertNotNull($product);

        $this->assertEquals('666FAW453OF', $product->code);
        $this->assertEquals('Oil filter', $product->name_en);
        $this->assertEquals('Масляный фильтр', $product->name_ru);
        $this->assertEquals(1, $product->brand_id);
        $this->assertEquals(1, $product->min_lot);
        $this->assertEquals(1.23, $product->volume);
        $this->assertEquals(2.34, $product->weight);

        $this->service->remove($product->id);

        $this->assertNull($this->getRawData('666FAW453OF'));
    }

    /**
     * @param string $code
     * @return stdClass|null
     */
    private function getRawData(string $code): stdClass|null
    {
        return DB::query()
            ->newQuery()
            ->from('product')
            ->where('code', '=', $code)
            ->first();
    }

    /**
     * @return void
     */
    public function testImport(): void
    {
        $importData = json_decode(file_get_contents('../../tests/data/product/import.json'), true);

        $totalRows = count($this->service->getList());

        $this->assertEquals(3, $totalRows);

        $result = $this->service->import($importData);

        $this->assertEquals(18, $result);

        $totalRows = count($this->service->getList());

        $this->assertEquals(21, $totalRows);
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function testImportFromFile(): void
    {
        /** @var RabbitMQManager $rabbitMQ */
        $rabbitMQ = app('rabbitmq');
        $routingKey = '1234'; // The key used by the consumer

        // The exchange (name) used by the consumer
        $exchange = new RabbitMQExchange('default', ['declare' => false]);

        $contents = new stdClass();
        $contents->filename = '/project/versions/current/export.csv';
        $contents->created = date('Y-m-d H:i:s');
        $contents->hash = bin2hex(openssl_random_pseudo_bytes(6));

        $message = new RabbitMQMessage(json_encode($contents));
        $message->setExchange($exchange);

        $rabbitMQ->publisher()->publish(
            $message,
            $routingKey
        );
    }
}