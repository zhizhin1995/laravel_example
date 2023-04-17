<?php declare(strict_types=1);

namespace Library\Exceptions;

use Exception;
use Throwable;

/**
 * @class InvalidDataException
 * @package Library\Exceptions
 */
class InvalidDataException extends Exception
{
    /**
     * @var int $status
     */
    public int $status = 400;

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