<?php declare(strict_types=1);

namespace Library\Validator;

use Illuminate\Support\Facades\App;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate;
use PHPOpenSourceSaver\JWTAuth\JWT;

/**
 * @class JWTValidator
 * @package Yurizhizhin\LaravelJwtAuth
 */
class JWTValidator extends Authenticate implements JWTValidatorInterface
{
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