<?php declare(strict_types=1);

namespace Library\DataHandlers\User;

use Library\Models\User;

/**
 * @class UserDataHandlerInterface
 * @package Library\DataHandlers\Currency;
 */
interface UserDataHandlerInterface
{
    /**
     * Регистрация нового пользователя
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    public function register(string $name, string $email, string $password): User;

    /**
     * Сохраняем токен
     *
     * @param string $email
     * @param string $token
     * @return bool
     */
    public function setTokenByEmail(string $email, string $token): bool;

    /**
     * Возвращает пользователя по email
     *
     * @param string $email
     * @return User
     */
    public function getUserByEmail(string $email): User;
}