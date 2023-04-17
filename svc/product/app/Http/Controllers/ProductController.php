<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Responses\Brand\BrandResponse;
use App\Services\Product\ProductService;
use Library\Exceptions\AuthException;
use Library\Responses\AbstractResponse;
use Library\Responses\Common\SuccessResponse;
use Library\Responses\Common\ListResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

/**
 * @class CountryController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    protected ProductService $service;

    /**
     * @var AbstractResponse $response
     */
    protected AbstractResponse $response;

    /**
     * @class BrandController constructor
     */
    public function __construct()
    {
        $this->service = App::make(ProductService::class);
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
            'nameEN' => 'required|string|max:255|alpha',
            'nameRU' => 'required|string|max:255|alpha',
            'code' => 'required|string|max:255|alpha',
            'brandID' => 'required|integer|gt:0',
            'countryID' => 'required|integer|gt:0',
            'minLot' => 'required|integer|gt:0',
            'volume' => 'required|numeric|gt:0',
            'weight' => 'required|numeric|gt:0',
        ]);

        $this->service->create(
            $request->code, $request->nameEN, $request->nameRU, $request->brandID,
            $request->minLot, $request->volume, $request->weight, $request->photo
        );

        $this->response = App::make(SuccessResponse::class);

        $data = $this->response->setData(true);

        return $this->response->getResponse($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function postImport(Request $request): JsonResponse
    {
        $request->validate([
            'products' => 'required|array|min:1:max:100000',
        ]);

        $this->service->import($request->products);

        $this->response = App::make(SuccessResponse::class);

        $data = $this->response->setData(true);

        return $this->response->getResponse($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function postImportFile(Request $request): JsonResponse
    {
        $request->validate([
            'import' => 'required|mimes:csv,json',
        ]);

        $file = $request->file('import');

        $this->service->importFromFile($file);

        $this->response = App::make(SuccessResponse::class);

        $data = $this->response->setData(true);

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