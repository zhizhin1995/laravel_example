<?php declare(strict_types=1);

namespace Tests\Feature;

use Library\Exceptions\AuthException;
use Library\Exceptions\NotPermittedException;
use App\Models\Dto\Currency\CurrencyDto;
use App\Models\Eloquent\Currency;
use App\Services\Currency\CurrencyService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Tests\TestCase;

/**
 * @class CurrencyRateTest
 * @package Tests\Feature
 */
class CurrencyControllerTest extends TestCase
{
    /**
     * TODO убрать хардкодное удаление после запила фикстур и БД каждый раз будет очищаться по человечески
     *
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        /** @var Currency $model */
        $model = App::make(Currency::class);

        $model->newQuery()->where('code', '=', 'YYY')
            ->where('company', '=', 'test')
            ->where('project', '=', 'b2c')
            ->delete();

        parent::tearDown();
    }

    /**
     * Тест добавления новых валют
     *
     * @return void
     * @throws NotPermittedException|AuthException
     * @throws Throwable
     */
    public function testCreate(): void
    {
        $this->token = $this->createToken($this->app);

        $response = $this->put('/currency/create', [
            'code' => 'CCC',
            'rate' => -3.33,
            'symbol' => 'C',
            'companyName' => 'test',
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The rate must be greater than 0.', $result->message);

        $service = App::make(CurrencyService::class);

        $this->assertPutRequests($service, 'YYY', 3.33, 'Y', 'test');

        //Пытаемся добавить такую же валюту
        $response = $this->put('/currency/create', [
            'code' => 'YYY',
            'rate' => 3.33,
            'symbol' => 'Y',
            'companyName' => 'test',
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('Currency with given data already exists (YYY, test, b2c)', $result->message);

        //Пытаемся добавить валюты без токена авторизации
        $response = $this->put('/currency/create', [
            'code' => 'YYY',
            'rate' => 3.33,
            'symbol' => 'Y',
            'companyName' => 'test',
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('Token not provided', $result->message);

        // Валидация некорректного ввода
        $response = $this->put('/currency/create', [
            'code' => 'YYY',
            'rate' => 3.33,
            'symbol' => 'YYY',
            'companyName' => 'test',
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The symbol must not be greater than 1 characters.', $result->message);

        $this->token = $this->createToken($this->app, false); // юзер без нужного разрешения

        // Пытаемся создать без разрешения
        $response = $this->put('/currency/create', [
            'code' => 'YYY',
            'rate' => 3.33,
            'symbol' => 'Y',
            'companyName' => 'test',
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('You have no permission to do this', $result->message);
    }

    /**
     * @return void
     * @throws NotPermittedException|AuthException|Throwable
     */
    public function testDelete(): void
    {
        $this->token = $this->createToken($this->app);

        $service = App::make(CurrencyService::class);
        $this->assertPutRequests($service, 'YYY', 3.33, 'Y', 'test');

        /** @var Currency $model */
        $currency = App::make(Currency::class);

        $model = $currency->newQuery()->where('code', '=', 'YYY')
            ->where('company', '=', 'test')
            ->first();

        $this->token = $this->createToken($this->app, false); // юзер без нужного разрешения

        $response = $this->delete('/currency/remove', [
            'id' => $model->id,
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('You have no permission to do this', $result->message);

        $this->token = $this->createToken($this->app); // юзер с разрешением

        $response = $this->delete('/currency/remove', [
            'id' => $model->id,
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertObjectHasAttribute('isSuccess', $result);
        $this->assertTrue($result->isSuccess);
    }

    /**
     * Валидация данных от put запросов (на добавление данных)
     *
     * @param CurrencyService $service
     * @param string $code
     * @param float $rate
     * @param string $symbol
     * @param string $company
     *
     * @return void
     * @throws NotPermittedException
     */
    private function assertPutRequests(CurrencyService $service, string $code, float $rate, string $symbol, string $company = CurrencyDto::DEFAULT_COMPANY_NAME): void
    {
        // Авторизованный
        $response = $this->put('/currency/create', [
            'code' => $code,
            'rate' => $rate,
            'symbol' => $symbol,
            'companyName' => $company,
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $currency = $service->getCurrentRate($code, $company);

        $this->assertEquals($rate, $currency->rate);
        $this->assertObjectHasAttribute('isSuccess', $result);
        $this->assertTrue($result->isSuccess);
    }

    /**
     * Тест запроса /currency-rate/current (запрашиваем текущий курс)
     *
     * @return void
     * @throws AuthException|Throwable
     */
    public function testConvert(): void
    {
        Auth::logout();

        $productData = json_decode(file_get_contents('./tests/data/products.json'));
        $productDataOriginal = json_decode(file_get_contents('./tests/data/products.json'));
        $productDataMoreThan100k = json_decode(file_get_contents('./tests/data/products100k.json'));

        $this->token = $this->createToken($this->app);

        $response = $this->post('/currency/convert', [
            'currencyFrom' => 'XXX',
            'currencyTo' => 'YYY',
            'products' => $productData
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('XXX', $result->currencyFrom);
        $this->assertEquals('YYY', $result->currencyTo);

        $productOriginal = get_object_vars($productDataOriginal[0]);
        $productConverted = get_object_vars($result?->products[0]);

        $this->assertEquals(22741.92, $productOriginal[key($productOriginal)]);
        $this->assertEquals(30323.0, $productConverted[key($productConverted)]);

        $response = $this->post('/currency/convert', [
            'currencyFrom' => 'XXX',
            'currencyTo' => 'YYY',
            'products' => $productDataMoreThan100k
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The products must have at least 1:max:100000 items.', $result->message);
    }

    /**
     * Тест запроса /currency/list (запрашиваем список валют)
     *
     * @return void
     * @throws AuthException
     */
    public function testGetList(): void
    {
        $this->token = $this->createToken($this->app);

        $response = $this->get('/currency/list', [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertObjectHasAttribute('list', $result);
        $this->assertGreaterThan(1, count($result->list));
        $this->assertIsNumeric($result->list[0]->id);
        $this->assertObjectHasAttribute('id', $result->list[0]);
        $this->assertObjectHasAttribute('symbol', $result->list[0]);
        $this->assertObjectHasAttribute('code', $result->list[0]);
        $this->assertObjectHasAttribute('company', $result->list[0]);
        $this->assertObjectHasAttribute('project', $result->list[0]);
        $this->assertObjectHasAttribute('created_at', $result->list[0]);
        $this->assertObjectHasAttribute('updated_at', $result->list[0]);

        $this->token = $this->createToken($this->app, false);

        $response = $this->get('/currency/list', [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('You have no permission to do this', $result->message);

        $this->assertEquals(403, $response->getStatusCode());

        $response = $this->get('/currency/list');

        $result = json_decode($response->getContent());

        $this->assertEquals('Token not provided', $result->message);
    }
}
