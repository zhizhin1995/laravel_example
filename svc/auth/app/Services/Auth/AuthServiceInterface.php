<?php declare(strict_types=1);

namespace App\Services\Auth;

use Library\Exceptions\AuthException;

/**
 * @class AuthServiceInterface
 * @package App\Services\Auth
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
}