<?php declare(strict_types=1);

namespace App\Http\Responses\Auth;

use Library\Helpers\JWTHelper;
use Library\Responses\AbstractResponse;
use Illuminate\Support\Facades\App;

/**
 * @class AuthSuccessResponse
 * @package App\Http\Responses\Auth
 */
class AuthSuccessResponse extends AbstractResponse
{
    /**
     * @var string $token Значение токена
     */
    public string $token;

    /**
     * @var string $validThrough Дата истечения
     */
    public string $validThrough;

    /**
     * @param string $token
     * @return self
     */
    public function setData(string $token): self
    {
        /** @var JWTHelper $jwtHelper */
        $jwtHelper = App::make(JWTHelper::class, [
            'jwtData' => $token
        ]);

        $this->token = $token;
        $this->validThrough = date('Y-m-d H:i:s', $jwtHelper->getPayLoadData('exp') ?? time());

        return $this;
    }
}