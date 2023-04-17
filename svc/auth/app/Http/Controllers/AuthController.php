<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Library\Exceptions\AuthException;
use App\Http\Responses\Auth\AuthFailedResponse;
use App\Http\Responses\Auth\AuthSuccessResponse;
use Library\Responses\Common\SuccessResponse;
use Library\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * @class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected AuthService $service;

    /**
     * @var AuthFailedResponse|AuthSuccessResponse|SuccessResponse $response
     */
    protected AuthFailedResponse|AuthSuccessResponse|SuccessResponse $response;

    /**
     * @class AuthController constructor
     */
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['postAuthorize', 'postRegister']
        ]);

        $this->service = App::make(AuthService::class);
        $this->response = App::make(SuccessResponse::class);
    }

    /**
     * Авторизация
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthException
     */
    public function postAuthorize(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $email = $request['email'];
        $password = $request['password'];

        $token = $this->service->auth($email, $password);

        $this->response = App::make(AuthSuccessResponse::class);

        $data = $this->response->setData($token);

        return $this->response->getResponse($data);
    }

    /**
     * Регистрация
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthException
     */
    public function postRegister(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|alpha',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $token = $this->service->register($request->name, $request->email, $request->password);

        $this->response = App::make(AuthSuccessResponse::class);

        $data = $this->response->setData($token);

        return $this->response->getResponse($data);
    }

    /**
     * Деактивация токена
     *
     * @return JsonResponse
     */
    public function postLogout(): JsonResponse
    {
        $this->service->logout();

        $data = $this->response->setData(true);

        return $this->response->getResponse($data);
    }
}