<?php declare(strict_types=1);

namespace Tests\Feature;

use Library\Exceptions\NotPermittedException;
use App\Services\Currency\CurrencyService;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Illuminate\Testing\TestResponse;
use Throwable;

/**
 * @class CurrencyRateTest
 * @package Tests\Feature
 */
class CurrencyRateControllerTest extends TestCase
{
    /**
     * Тест запроса /currency-rate/set-rate (устанавливаем новый курс)
     *
     * @return void
     * @throws NotPermittedException|Throwable
     */
    public function testSetRate(): void
    {
        $this->token = $this->createToken($this->app);

        /** @var CurrencyService $currencyService */
        $currencyService = App::make(CurrencyService::class);

        $this->assertPutRequests($currencyService, 'XXX', 3.26);
        $this->assertPutRequests($currencyService, 'USD', 1);

        try {
            $this->assertPutRequests($currencyService, 'SDF', 9.99);
        } catch (Throwable $ex) {
            $this->assertEquals('Could not retrieve data for SDF', $ex->getMessage());
        }

        $response = $this->put('/currency-rate/set-rate', [
            'code' => 'USD',
            'rate' => 'asdf'
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The rate must be a number. (and 1 more error)', $result->message);

        $response = $this->put('/currency-rate/set-rate', [], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The code field is required. (and 1 more error)', $result->message);

        $response = $this->put('/currency-rate/set-rate', [], [
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('Token not provided', $result->message);

        // Некорректный токен
        $response = $this->put('/currency-rate/set-rate', [], [
            'Authorization' => "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('Token Signature could not be verified.', $result->message);

        $response = $this->put('/currency-rate/set-rate', [
            'code' => 'USD',
            'rate' => -3.66
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The rate must be greater than 0.', $result->message);

        $response = $this->put('/currency-rate/set-rate', [
            'code' => 'USD',
            'rate' => 'XXX'
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The rate must be a number. (and 1 more error)', $result->message);

        $response = $this->put('/currency-rate/set-rate', [
            'code' => 'THIS STRING IS TOO FUCKING LONG',
            'rate' => 3
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The code must not be greater than 3 characters. (and 1 more error)', $result->message);

        $response = $this->put('/currency-rate/set-rate', [
            'code' => 'USD',
            'rate' => '$$**(*(@#$*(@#(*$*('
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The rate must be a number. (and 1 more error)', $result->message);

        $this->token = $this->createToken($this->app, false);

        $response = $this->put('/currency-rate/set-rate', [
            'code' => 'USD',
            'rate' => 1.11
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('You have no permission to do this', $result->message);
    }

    /**
     * Тест запроса /currency-rate/current (запрашиваем текущий курс)
     *
     * @depends testSetRate
     *
     * @return void
     * @throws Throwable
     */
    public function testCurrentRate(): void
    {
        $this->token = $this->createToken($this->app);

        $response = $this->get('/currency-rate/current?code=usd', [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $this->assertGetRequests($response, $today = true);

        $response = $this->get('/currency-rate/current?code=aefwfasdf', [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The code must not be greater than 3 characters.', json_decode($response->content())->message);

        // пытаемся получить курс по несуществующей валюте
        $response = $this->get('/currency-rate/current?code=PPP', [
            'Authorization' => "Bearer {$this->token}"
        ]);

        // пустой запрос
        $response = $this->get('/currency-rate/current');

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The code field is required.', json_decode($response->content())->message);

        $this->token = $this->createToken($this->app, false);

        $response = $this->get('/currency-rate/current?code=aefwfasdf');
        $this->assertEquals('The code must not be greater than 3 characters.', json_decode($response->content())->message);
    }

    /**
     * Тест запроса /currency-rate/by-date (запрашиваем курс за дату)
     *
     * @return void
     * @throws Throwable
     */
    public function testGetRateByDate(): void
    {
        $this->token = $this->createToken($this->app);

        $response = $this->get('/currency-rate/by-date?code=USD&date=2022-11-24', [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $this->assertGetRequests($response);


        $response = $this->get('/currency-rate/by-date?code=USD&date=NOT-DATE-AT-ALL', [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The date does not match the format Y-m-d.', $result->message);

        $response = $this->get('/currency-rate/by-date?code=USD&date=:)))))', [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The date does not match the format Y-m-d.', $result->message);

        $response = $this->get('/currency-rate/by-date?code=TOO_FUCKING_LONG_DEFINITELY_NON_EXISTING_CURRENCY&date=DOOMSDAY', [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The code must not be greater than 3 characters. (and 2 more errors)', $result->message);

        $response = $this->get("/currency-rate/by-date?code=***&date=DOOMSDAY", [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The code must only contain letters. (and 1 more error)', $result->message);
    }

    /**
     * Валидация данных от put запросов (на добавление данных)
     *
     * @param CurrencyService $service
     * @param string $code
     * @param float|string $rate
     * @return void
     * @throws NotPermittedException
     */
    private function assertPutRequests(CurrencyService $service, string $code, float|string $rate): void
    {
        $response = $this->put('/currency-rate/set-rate', [
            'code' => $code,
            'rate' => $rate
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $result = json_decode($response->getContent());

        $currency = $service->getCurrentRate($code);

        $this->assertEquals($rate, $currency->rate);
        $this->assertTrue($result->isSuccess);
    }

    /**
     * Валидация данных, которые вернулись от get запросов (на чтение данных)
     *
     * @param TestResponse $response
     * @param bool $today
     * @return void
     */
    private function assertGetRequests(TestResponse $response, bool $today = false): void
    {
        $date = $today ? date('Y-m-d', time()) : '2022-11-24';

        $result = json_decode($response->content());

        $this->assertEquals('$', $result->symbol);
        $this->assertEquals($date, $result->date);
        $this->assertEquals(1, $result->rate);
        $this->assertIsNumeric($result->rate);
        $this->assertEquals('common', $result->companyName);

        $response->assertStatus(200);
    }
}
