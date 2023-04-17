<?php declare(strict_types=1);

namespace Library\Helpers;

use Library\Models\Dto\RBAC\JWTDto;
use Illuminate\Support\Facades\App;

/**
 * @class JWTHelper
 * @package Library\Helpers
 */
class JWTHelper
{
    /**
     * @var JWTDto $jwt
     */
    public JWTDto $jwt;

    /**
     * @param string $jwtData
     */
    public function __construct(string $jwtData)
    {
        $this->jwt = $this->parse($jwtData);
    }

    /**
     * Получить данные токена
     *
     * @param string $key
     * @return mixed
     */
    public function getPayLoadData(string $key = ''): mixed
    {
        if (!empty($key)) {
            return $this->jwt->payload->$key ?? null;
        }

        return $this->jwt->payload ?? null;
    }

    /**
     * Получаем объект токена из строки
     *
     * @param string $jwtData
     * @return JWTDto
     */
    private function parse(string $jwtData): JWTDto
    {
        $tokenParts = explode(".", $jwtData);
        $tokenHeader = base64_decode($tokenParts[0] ?? '');
        $tokenPayload = base64_decode($tokenParts[1] ?? '');
        $jwtSecret = $tokenParts[2] ?? '';
        $jwtHeader = json_decode($tokenHeader ?? '');
        $jwtPayload = json_decode($tokenPayload ?? '');

        return App::make(JWTDto::class, [
            'header' => $jwtHeader,
            'payload' => $jwtPayload,
            'secret' => $jwtSecret,
        ]);
    }
}