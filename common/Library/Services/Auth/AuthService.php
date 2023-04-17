<?php declare(strict_types=1);

namespace Library\Services\Auth;

use Library\DataHandlers\RBAC\RBACDataHandler;
use Library\Models\Eloquent\ModelHasRoles;
use Illuminate\Support\Facades\App;
use Library\DataHandlers\User\UserDataHandler;
use Library\Exceptions\AuthException;
use Library\Services\ServiceInterface;
use Library\Models\User;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate;
use PHPOpenSourceSaver\JWTAuth\JWT;

/**
 * @class AuthService
 * @package Library\Services\Auth
 */
class AuthService extends Authenticate implements AuthServiceInterface, ServiceInterface
{
    /**
     * @var UserDataHandler Источник и обработчик данных (ex.: PostgresSQL, mongoDB)
     */
    public UserDataHandler $dataHandler;

    public function __construct()
    {
        $this->dataHandler = App::make(UserDataHandler::class, ['dataSource' => new User()]);
    }

    /**
     * {@inheritDoc}
     */
    public function auth(string $email, string $password): bool|string
    {
        $user = $this->dataHandler->getUserByEmail($email);

        /** @var RBACDataHandler $rbac */
        $rbac = App::make(RBACDataHandler::class, [
            'dataSource' => new ModelHasRoles()
        ]);

        $token = auth()->claims([
            'roles' => $rbac->getModelRoles($user->id),
            'aud' => env('APP_NAME'),
        ])->attempt([
            'email' => $email,
            'password' => $password,
        ]);

        if (!$token) {
            throw new AuthException('Invalid credentials, please try again');
        }


        $this->dataHandler->setTokenByEmail($email, $token);

        return $token;
    }

    /**
     * {@inheritDoc}
     *
     * @throws AuthException
     */
    public function register(string $name, string $email, string $password): bool|string
    {
        $this->dataHandler->register($name, $email, $password);

        return $this->auth($email, $password);
    }

    /**
     * @param string $email
     * @return object
     */
    public function getUserByEmail(string $email): object
    {
        return $this->dataHandler->getUserByEmail($email);
    }

    /**
     * @return bool
     */
    public function logout(): bool
    {
        Auth::logout();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function validateToken(string $token): bool
    {
        /** @var JWT $jwtValidator */
        $jwtValidator = App::make(JWT::class);

        $jwtValidator->setToken($token);

        return $jwtValidator->check();
    }
}