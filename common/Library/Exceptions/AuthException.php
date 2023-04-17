<?php declare(strict_types=1);

namespace Library\Exceptions;

use Exception;
use Throwable;

/**
 * @class AuthException
 * @package Library\Exceptions
 */
class AuthException extends Exception
{
    /**
     * @var int $status
     */
    public int $status = 401;

    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}