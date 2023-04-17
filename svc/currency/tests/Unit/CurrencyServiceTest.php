<?php declare(strict_types=1);

namespace Tests\Unit;

use Library\Exceptions\AuthException;
use App\Services\Currency\CurrencyService;
use Library\Exceptions\InvalidDataException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Library\Exceptions\NotPermittedException;
use Tests\TestCase;
use Throwable;

/**
 * @class CurrencyTest
 * @package Tests\Unit
 */
class CurrencyServiceTest extends TestCase
{
    /**
     * @var CurrencyService $service
     */
    private CurrencyService $service;

    /**
     * @return void
     * @throws AuthException|\Library\Exceptions\AuthException
     */
    public function setUp(): void
    {
        $this->service = $this->app->make(CurrencyService::class);

        $this->token = $this->createToken($this->app);

        $this->prepareCurrencyData();

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
     * Тест получения текущего курса
     *
     * @return void
     * @throws NotPermittedException
     */
    public function testGetCurrentRate(): void
    {
        $result = $this->service->getCurrentRate('USD');

        $this->assertEquals(1, $result->rate);
        $this->assertIsNumeric($result->rate);
        $this->assertEquals('USD', $result->currency->code);
        $this->assertEquals('$', $result->currency->symbol);
        $this->assertEquals('common', $result->currency->company);

        $result = $this->service->getCurrentRate('XXX');

        $this->assertIsNumeric($result->rate);
        $this->assertEquals('XXX', $result->currency->code);
        $this->assertEquals('X', $result->currency->symbol);
        $this->assertEquals('common', $result->currency->company);

        try {
            $this->service->getCurrentRate('AAA');
        } catch (NotFoundHttpException $ex) {
            $this->assertEquals('Could not retrieve data for AAA', $ex->getMessage());
        }

        try {
            $this->service->getCurrentRate('');
        } catch (NotFoundHttpException $ex) {
            $this->assertEquals('Could not retrieve data for ', $ex->getMessage());
        }
    }

    /**
     * Тест получения курса за определенную дату
     *
     * @return void
     * @throws NotPermittedException
     */
    public function testGetRateByDate(): void
    {
        $date = '2022-11-24';

        // получаем курс за прошлую дату
        $result = $this->service->getRateByDay('USD', $date);

        $this->assertEquals(1, $result->rate);
        $this->assertIsNumeric($result->rate);
        $this->assertEquals('USD', $result->currency->code);
        $this->assertEquals('$', $result->currency->symbol);
        $this->assertEquals('common', $result->currency->company);

        // пытаемся получить свежий курс (которого в БД нет), в результате должны получить последний предыдущий
        $result = $this->service->getRateByDay('USD', date('Y-m-d', time()));

        $this->assertEquals(1, $result->rate);
        $this->assertIsNumeric($result->rate);
        $this->assertEquals('USD', $result->currency->code);
        $this->assertEquals('$', $result->currency->symbol);
        $this->assertEquals('common', $result->currency->company);
    }

    /**
     * @return void
     * @throws NotPermittedException|InvalidDataException
     */
    public function testSetRate(): void
    {
        $this->assertTrue($this->service->setRate('XXX', 3.22));

        try {
            $this->service->setRate('XXX', -3.22);
        } catch (InvalidDataException $ex) {
            $this->assertEquals('Rate must be positive and greater than 0', $ex->getMessage());
        }

        try {
            $this->service->setRate('BBB', 3);
        } catch (Throwable $ex) {
            $this->assertEquals("Could not retrieve data for 'BBB' currency", $ex->getMessage());
        }
    }
}
