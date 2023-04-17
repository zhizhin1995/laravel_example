<?php declare(strict_types=1);

namespace Library\Exceptions;

use Exception;
use Throwable;

/**
 * @class InvalidTokenProvidedException
 * @package Library\Exceptions
 */
class InvalidTokenProvidedException extends Exception
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
    public function __construct(string $message = "Unable to validate provided token", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}