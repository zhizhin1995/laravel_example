<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Dto\BrandDto;
use App\Services\Brand\BrandService;
use Illuminate\Support\Facades\DB;
use Library\Exceptions\AuthException;
use Library\Exceptions\InvalidDataException;
use Tests\TestCase;
use stdClass;

/**
 * @class CurrencyTest
 * @package Tests\Unit
 */
class BrandServiceTest extends TestCase
{
    /**
     * @var BrandService $service
     */
    private BrandService $service;

    /**
     * @return void
     * @throws AuthException|AuthException
     */
    public function setUp(): void
    {
        $this->service = $this->app->make(BrandService::class);

        $this->token = $this->createToken($this->app);

        parent::setUp();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Тест получения бренда
     *
     * @return void
     */
    public function testGetBrand(): void
    {
        // Нормальные бренды
        $this->assertBrand($this->service->getByID(1), 1);
        $this->assertBrand($this->service->getByID(2), 2);
        $this->assertBrand($this->service->getByID(3), 3);

        // Несуществующий бренд
        try {
            $this->service->getByID(666);
        } catch (InvalidDataException $ex) {
            $this->assertEquals('Could not retrieve brand for given id', $ex->getMessage());
        }
    }

    /**
     * @param BrandDto $result
     * @param int $id
     * @return void
     */
    private function assertBrand(BrandDto $result, int $id): void
    {
        $this->assertNotEmpty($result->name);
        $this->assertIsNumeric($result->id);
        $this->assertIsNumeric($result->countryID);
        $this->assertEquals($id, $result->id);
        $this->assertEquals($id, $result->countryID);
        $this->assertNotEmpty($result->countryName);
    }

    /**
     * @return void
     */
    public function testGetList(): void
    {
        $result = $this->service->getList();

        $this->assertIsArray($result);
        $this->assertGreaterThan(1, count($result));
        $this->assertBrand($result[0], 1);

        //Очищаем БД и получаем список
        DB::table('brand')->truncate();

        $this->assertEmpty($this->service->getList());
    }

    /**
     * @return void
     */
    public function testCreateAndDelete(): void
    {
        $name = 'Test Brand';

        $this->service->create($name, 1);

        $result = $this->getRawData('TEST BRAND');

        // Проверяем uppercase
        $this->assertEquals('TEST BRAND', $result->name);

        //Удаляем созданный бренд
        $this->service->remove($result->id);

        $result = $this->getRawData($name);

        $this->assertNull($result);
    }

    /**
     * @param $name
     * @return stdClass|null
     */
    private function getRawData($name): stdClass|null
    {
        return DB::query()
            ->newQuery()
            ->from('brand')
            ->where('name', '=', $name)
            ->first();
    }

    /**
     * @return void
     */
    public function testGetMapping(): void
    {
        $this->assertMapping($this->service->getMappingByID(1));
        $this->assertMapping($this->service->getMappingByID(2));
        $this->assertMapping($this->service->getMappingByID(3));

        $result = $this->service->getMappingByID(666);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @param array $result
     * @return void
     */
    private function assertMapping(array $result): void
    {
        $this->assertIsArray($result);

        $this->assertGreaterThan(1, count($result));

        foreach ($result as $mapping) {
            $this->assertNotEmpty($mapping->name);
            $this->assertIsString($mapping->source);
        }
    }

    /**
     * @return void
     */
    public function testSetMapping(): void
    {
        $brand = $this->service->getByID(1);

        $this->service->setBrandMapping("{$brand->name} PHPUNIT", $brand->id, 'From test case');

        $mappingList = $this->service->getMappingByID(1);

        $mapping = end($mappingList);

        $this->assertEquals('TOYOTA PHPUNIT', $mapping->name);
        $this->assertEquals('From test case', $mapping->source);
    }

    /**
     * @return void
     */
    public function testBrandAnalogues(): void
    {
        $analogueList = $this->service->getAnalogueList('TOYOTA');

        $this->assertArrayHasKey(0, $analogueList);

        $analogue = $analogueList[0];

        $this->assertNotEmpty($analogue->original);
        $this->assertNotEmpty($analogueList[0]->analogue);

        $this->assertEquals('TOYOTA', $analogue->original->name);
        $this->assertEquals('Japan', $analogue->original->countryName);

        $this->assertEquals('HYUNDAI', $analogue->analogue->name);
        $this->assertEquals('Korea', $analogue->analogue->countryName);
    }
}
