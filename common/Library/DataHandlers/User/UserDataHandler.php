<?php declare(strict_types=1);

namespace Library\DataHandlers\User;

use Library\DataHandlers\AbstractDataHandler;
use Library\Models\User;

/**
 * @class UserDataHandler
 * @package Library\DataHandlers
 */
class UserDataHandler extends AbstractDataHandler implements UserDataHandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(string $name, string $email, string $password): User
    {
        return $this->dataSource->register($name, $email, $password);
    }

    /**
     * {@inheritDoc}
     */
    public function setTokenByEmail(string $email, string $token): bool
    {
        return $this->dataSource->setTokenByEmail($email, $token);
    }

    /**
     * {@inheritDoc}
     */
    public function getUserByEmail(string $email): User
    {
        return $this->dataSource->getUserByEmail($email);
    }
}