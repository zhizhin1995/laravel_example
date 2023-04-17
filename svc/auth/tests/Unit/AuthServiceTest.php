<?php declare(strict_types=1);

namespace Tests\Unit;

use Library\Exceptions\AuthException;
use App\Models\User;
use Library\Services\Auth\AuthService;
use Tests\TestCase;

/**
 * @class AuthServiceTest
 * @package Tests\Unit
 */
class AuthServiceTest extends TestCase
{
    /**
     * @var array $testUser
     */
    protected static array $testUser = [
        'name' => 'Test',
        'email' => 'example@example.com',
        'password' => '1234',
    ];

    /**
     * @var AuthService $service
     */
    private AuthService $service;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->service = $this->app->make(AuthService::class);

        parent::setUp();
    }

    protected function tearDown(): void
    {
        User::query()->where('email', '=', self::$testUser['email'])->delete();

        parent::tearDown();
    }

    /**
     * Тест регистрации нового пользователя
     *
     * @return void
     * @throws AuthException
     */
    public function testRegister(): void
    {
        $result = $this->service->register(self::$testUser['name'], self::$testUser['email'], self::$testUser['password']);

        $this->assertNotEmpty($result);
        $this->assertIsString($result);

        // Пытаемся снова дернуть сервис с теми же данными
        try {
            $this->service->register(self::$testUser['name'], self::$testUser['email'], self::$testUser['password']);
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->assertStringContainsString('duplicate key value violates unique constraint "users_email_unique"', $ex->getMessage());
        }
    }

    /**
     * Тест авторизации
     *
     * @depends testRegister
     *
     * @return void
     * @throws AuthException
     */
    public function testAuth(): void
    {
        $this->service->register(self::$testUser['name'], self::$testUser['email'], self::$testUser['password']);

        $result = $this->service->auth(self::$testUser['email'], self::$testUser['password']);

        $this->assertNotEmpty($result);
        $this->assertIsString($result);
    }
}
