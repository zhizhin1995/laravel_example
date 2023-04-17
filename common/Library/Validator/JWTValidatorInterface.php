<?php
declare(strict_types=1);

namespace Library\Validator;

/**
 * @class JWTValidatorInterface
 * @package Yurizhizhin\LaravelJwtAuth\Validator
 */
interface JWTValidatorInterface
{
    /**
     * Валидация токена
     *
     * @param string $token
     * @return bool
     */
    public function validateToken(string $token): bool;
}