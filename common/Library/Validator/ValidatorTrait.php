<?php declare(strict_types=1);

namespace Library\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Library\Exceptions\InvalidTokenProvidedException;
use Library\Exceptions\NotPermittedException;
use Library\Helpers\JWTHelper;
use Library\RBAC\PermissionValidator;

/**
 * @class ValidatorTrait
 * @package Yurizhizhin\LaravelJwtAuth
 */
trait ValidatorTrait
{
    /**
     * @var string|bool $authToken
     */
    public string|bool $authToken;

    /**
     * Валидация токена
     *
     * @param Request $request
     * @param string $permission
     * @return bool
     * @throws InvalidTokenProvidedException|NotPermittedException
     */
    public function checkAuthToken(Request $request, string $permission): bool
    {
        $this->authToken = $request->server->get('HTTP_AUTHORIZATION', false);

        if (!is_string($this->authToken)) {
            throw new InvalidTokenProvidedException();
        }

        $this->authToken = str_replace('Bearer ', '', $this->authToken);

        /** @var JWTValidator $validator */
        $validator = App::make(JWTValidator::class);

        /** @var PermissionValidator $permissionValidator */
        $permissionValidator = App::make(PermissionValidator::class);

        /** @var JWTHelper $jwtHelper */
        $jwtHelper = App::make(JWTHelper::class, [
            'jwtData' => $this->authToken
        ]);

        $userID = (int)$jwtHelper->getPayLoadData('sub');

        return $validator->validateToken($this->authToken) && $permissionValidator->getHasPermission($permission, $userID);
    }
}