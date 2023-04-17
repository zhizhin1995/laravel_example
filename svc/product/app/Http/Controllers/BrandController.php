<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Responses\Brand\BrandAnalogueResponse;
use App\Http\Responses\Brand\BrandResponse;
use App\Services\Brand\BrandService;
use Library\Exceptions\AuthException;
use Library\Responses\AbstractResponse;
use Library\Responses\Common\SuccessResponse;
use Library\Responses\Common\ListResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * @class BrandController
 * @package App\Http\Controllers
 */
class BrandController extends Controller
{
    /**
     * @var BrandService
     */
    protected BrandService $service;

    /**
     * @var AbstractResponse $response
     */
    protected AbstractResponse $response;

    /**
     * @class BrandController constructor
     */
    public function __construct()
    {
        $this->service = App::make(BrandService::class);
        $this->response = App::make(SuccessResponse::class);
    }

    /**
     * Список брендов
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthException
     */
    public function getList(Request $request): JsonResponse
    {
        /** @var ListResponse response */
        $this->response = App::make(ListResponse::class);

        $data = $this->response->setData($this->service->getList());

        return $this->response->getResponse($data);
    }

    /**
     * Получить данные по ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getByID(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer|gt:0',
        ]);

        $result = $this->service->getByID($request->id);

        /** @var BrandResponse response */
        $this->response = App::make(BrandResponse::class);
        $data = $this->response->load($result);

        return $this->response->getResponse($data);
    }

    public function getBrandAnalogueList(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|alpha'
        ]);

        /** @var BrandAnalogueResponse response */
        $this->response = App::make(BrandAnalogueResponse::class);

        $data = $this->response->load($this->service->getAnalogueList(strtoupper($request->name)));

        return $this->response->getResponse($data);
    }

    /**
     * Добавление нового бренда
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthException
     */
    public function putCreate(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|alpha',
            'countryID' => 'required|integer|gt:0',
        ]);

        $this->service->create($request->name, (int)$request->countryID);

        $this->response = App::make(SuccessResponse::class);

        $data = $this->response->setData(true);

        return $this->response->getResponse($data);
    }

    /**
     * Добавление доп названия для бренда
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function postSetMapping(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|alpha',
            'source' => 'string|max:255|alpha',
            'originalID' => 'required|integer|gt:0',
        ]);

        $data = $this->response->setData($this->service->setBrandMapping(
            $request->name, (int)$request->originalID, $request->source
        ));

        return $this->response->getResponse($data);
    }

    /**
     * Удаление бренда
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteRemove(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer|gt:0',
        ]);

        $this->service->remove($request->id);

        $this->response = App::make(SuccessResponse::class);

        $data = $this->response->setData(true);

        return $this->response->getResponse($data);
    }
}