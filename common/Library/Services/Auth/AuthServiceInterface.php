<?php declare(strict_types=1);

namespace Library\Services\Auth;

use Library\Exceptions\AuthException;

/**
 * @class AuthServiceInterface
 * @package Library\Services\Auth
 */
interface AuthServiceInterface
{
    /**
     * Авторизация пользователя, получение токена
     *
     * @param string $email
     * @param string $password
     * @throws AuthException
     *
     * @return bool|string
     */
    public function auth(string $email, string $password): bool|string;

    /**
     * Дезактивация текущего, действующего токена
     *
     * @return bool
     */
    public function logout(): bool;

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return bool|string
     */
    public function register(string $name, string $email, string $password): bool|string;

    /**
     * Валидация токена
     *
     * @param string $token
     * @throws AuthException
     *
     * @return bool
     */
    public function validateToken(string $token): bool;
}