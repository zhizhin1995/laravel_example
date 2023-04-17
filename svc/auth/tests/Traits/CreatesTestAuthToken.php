<?php declare(strict_types=1);

namespace Tests\Traits;

use Library\Exceptions\AuthException;
use Library\Services\Auth\AuthService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Throwable;

/**
 * @class CreatesTestAuthToken
 * @package Library\Tests\Traits
 */
trait CreatesTestAuthToken
{
    /**
     * Создает тестового пользователя.
     * @param App|Application $app
     * @param bool $authorized
     * @return bool|string
     * @throws AuthException
     */
    public function createToken(App|Application $app, bool $authorized = true): bool|string
    {
        /** @var AuthService $service */
        $service = $app->make(AuthService::class);

        $name = $authorized ? env('TEST_AUTH_NAME') : env('TEST_NOT_AUTH_NAME');
        $email = $authorized ? env('TEST_AUTH_EMAIL') : env('TEST_NOT_AUTH_EMAIL');
        $pass = $authorized ? env('TEST_AUTH_PASS') : env('TEST_NOT_AUTH_PASS');

        try {
            $token = $service->register(
                $name,
                $email,
                $pass
            );

            if ($authorized) {
                $this->user = $service->getUserByEmail($email);
                $this->createAssignment($this->user);
            }

            return $token;
        } catch (Throwable) {
            return $service->auth(
                $email,
                $pass
            );
        }
    }
}
