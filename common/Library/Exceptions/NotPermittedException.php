<?php declare(strict_types=1);

namespace Library\Exceptions;

use Exception;
use Throwable;

/**
 * @class NotPermittedException
 * @package Library\Exceptions
 */
class NotPermittedException extends Exception
{
    /**
     * @var int $status
     */
    public int $status = 403;

    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "You have no permission to do this", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}