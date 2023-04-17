<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\App;
use stdClass;
use Tests\TestCase;

/**
 * @class AuthControllerTest
 * @package Tests\Feature
 */
class AuthControllerTest extends TestCase
{
    /**
     * @var array $testUser
     */
    protected static array $testUser = [
        'name' => 'Test',
        'email' => 'example@example.com',
        'password' => 'password1234',
    ];

    /**
     * TODO убрать хардкодное удаление после запила фикстур и БД каждый раз будет очищаться по человечески
     *
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        /** @var User $model */
        $model = App::make(User::class);

        $model->newQuery()
            ->where('email', '=', self::$testUser['email'])
            ->delete();

        parent::tearDown();
    }

    /**
     * Тест запроса /auth/register (регистрация нового пользователя)
     *
     * @return void
     */
    public function testRegister(): void
    {
        $result = $this->registerTestUser(self::$testUser['name'], self::$testUser['email'], self::$testUser['password']);

        $this->assertObjectHasAttribute('token', $result);
        $this->assertObjectHasAttribute('validThrough', $result);
        $this->assertNotNull($result->token);
        $this->assertNotNull($result->validThrough);

        $result = $this->registerTestUser(self::$testUser['name'], self::$testUser['email'], self::$testUser['password']);

        $this->assertObjectHasAttribute('message', $result);
        $this->assertEquals('The email has already been taken.', $result->message);

        $result = $this->registerTestUser('', '', self::$testUser['password']);

        $this->assertEquals('The name field is required. (and 1 more error)', $result->message);
    }

    /**
     * Тест запроса /auth/authorize (авторизация по логину и паролю)
     *
     * @return void
     */
    public function testAuthorize(): void
    {
        $regResult = $this->registerTestUser(self::$testUser['name'], self::$testUser['email'], self::$testUser['password']);

        $this->assertObjectHasAttribute('token', $regResult);
        $this->assertObjectHasAttribute('validThrough', $regResult);
        $this->assertNotNull($regResult->token);
        $this->assertNotNull($regResult->validThrough);

        $response = $this->post('/auth/authorize', [
            'email' => self::$testUser['email'],
            'password' => self::$testUser['password']
        ]);

        $result = json_decode($response->getContent());

        $this->assertObjectHasAttribute('token', $result);
        $this->assertObjectHasAttribute('validThrough', $result);
        $this->assertEquals(date('Y-m-d H:i:s', time() + (int)env('JWT_TTL') * 60), $result->validThrough);
        $this->assertNotNull($result->token);
        $this->assertNotNull($result->validThrough);

        $response = $this->post('/auth/authorize', [
            'email' => '123',
            'password' => '234'
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('The email must be a valid email address.', $result->message);

        $response = $this->post('/auth/authorize', [
            'email' => 'aaa@aaa.com',
            'password' => '112233'
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('Could not retrieve data for aaa@aaa.com', $result->message);
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return stdClass
     */
    private function registerTestUser(string $name, string $email, string $password): stdClass
    {
        $response = $this->post('/auth/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        return json_decode($response->getContent());
    }
}
