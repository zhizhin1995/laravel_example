<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Dto\CountryDto;
use App\Services\Country\CountryService;
use Illuminate\Support\Facades\DB;
use Library\Exceptions\AuthException;
use Library\Exceptions\InvalidDataException;
use Tests\TestCase;
use stdClass;

/**
 * @class CountryServiceTest
 * @package Tests\Unit
 */
class CountryServiceTest extends TestCase
{
    /**
     * @var CountryService $service
     */
    private CountryService $service;

    /**
     * @return void
     * @throws AuthException|AuthException
     */
    public function setUp(): void
    {
        $this->service = $this->app->make(CountryService::class);

        $this->token = $this->createToken($this->app);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testGetList(): void
    {
        $result = $this->service->getList();

        foreach ($result as $country) {
            $this->assertCountry($country);
        }

        //Очищаем БД и получаем список
        DB::table('country')->truncate();

        $result = $this->service->getList();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @param CountryDto $country
     * @return void
     */
    private function assertCountry(CountryDto $country): void
    {
        $this->assertNotNull($country->id);
        $this->assertIsNumeric($country->id);
        $this->assertNotEmpty($country->name);
        $this->assertNotEmpty($country->code);
        $this->assertEquals(null, $country->flag);
    }

    /**
     * @return void
     */
    public function testGetById(): void
    {
        $result = $this->service->getByID(1);

        $this->assertCountry($result);

        try {
            $this->service->getByID(666);
        } catch (InvalidDataException $ex) {
            $this->assertEquals('Could not retrieve country for given id', $ex->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testCreateAndDelete(): void
    {
        $result = $this->service->create('Test Country', 'TST', '$');

        $this->assertTrue($result);

        $newModel = $this->getRawData('TST');
        $this->assertEquals('Test Country', $newModel->name);
        $this->assertEquals('TST', $newModel->code);
        $this->assertEquals('$', $newModel->flag);

        $this->service->remove($newModel->id);

        $this->assertNull($this->getRawData('TST'));
    }

    /**
     * @param string $code
     * @return stdClass|null
     */
    private function getRawData(string $code): stdClass|null
    {
        return DB::query()
            ->newQuery()
            ->from('country')
            ->where('code', '=', $code)
            ->first();
    }

    /**
     * @return void
     */
    public function testRabbit(): void
    {

    }
}