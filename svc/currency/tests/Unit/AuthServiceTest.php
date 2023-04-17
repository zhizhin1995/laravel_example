<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use Library\Exceptions\AuthException;
use Library\Services\Auth\AuthService;
use Tests\TestCase;
use Illuminate\Support\Facades\App;
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
        'password' => 'aefwaefawefasd',
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

    /**
     * {@inheritDoc}
     */
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
    public function testValidate(): void
    {
        $token = $this->getToken();

        $result = $this->service->validateToken($token);

        $this->assertNotEmpty($token);
        $this->assertIsString($token);
        $this->assertTrue($result);
    }

    /**
     * @return string
     * @throws AuthException
     */
    private function getToken(): string
    {
        /** @var AuthService $service */
        $service = App::make(AuthService::class);

        return $service->register(self::$testUser['name'], self::$testUser['email'], self::$testUser['password']);
    }
}
