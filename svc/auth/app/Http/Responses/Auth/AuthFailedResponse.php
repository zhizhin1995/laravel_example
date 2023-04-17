<?php declare(strict_types=1);

namespace App\Http\Responses\Auth;

use Library\Responses\AbstractResponse;

/**
 * @class AuthFailedResponse
 * @package App\Http\Responses\Auth
 */
class AuthFailedResponse extends AbstractResponse
{
    /**
     * @var string $message Сообщение
     */
    public string $message;

    /**
     * @param string $message
     * @return self
     */
    public function setData(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}