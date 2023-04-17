<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Constants\Permission;
use Library\Responses\Common\SuccessResponse;
use App\Http\Responses\Currency\CurrencyConvertResponse;
use App\Http\Responses\Currency\CurrencyListResponse;
use App\Models\Dto\Currency\CurrencyDto;
use App\Services\Currency\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Library\Exceptions\InvalidTokenProvidedException;
use Library\Exceptions\NotPermittedException;
use Throwable;

/**
 * @class CurrencyController
 * @package App\Http\Controllers
 */
class CurrencyController extends Controller
{
    /**
     * @var CurrencyService|mixed
     */
    protected CurrencyService $service;

    /**
     * @var SuccessResponse|CurrencyConvertResponse|CurrencyListResponse
     */
    protected SuccessResponse|CurrencyConvertResponse|CurrencyListResponse $response;

    /**
     * @class CurrencyController constructor
     */
    public function __construct()
    {
        $this->middleware('auth:api');

        $this->service = App::make(CurrencyService::class);
        $this->response = App::make(SuccessResponse::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function putCreate(Request $request): JsonResponse
    {
        $this->checkAuthToken($request, Permission::CAN_CREATE);

        $code = strtoupper($request['code'] ?? '');
        $rate = $request['rate'] ?? null;
        $companyName = $request['companyName'] ?? '';
        $symbol = $request['symbol'] ?? '';
        $project = $request['project'] ?? CurrencyDto::DEFAULT_PROJECT;
        $isMain = $request['is_main'] ?? false;

        $request->validate([
            'code' => 'required|string|min:3|max:3|alpha',
            'symbol' => 'required|string|min:1|max:1',
            'companyName' => 'required|string|min:2|max:64|alpha',
            'rate' => 'required|numeric|gt:0',
            'is_main' => 'boolean',
        ]);

        $data = $this->response->setData($this->service->create($code, $rate, $symbol, $companyName, $project, $isMain));

        return $this->response->getResponse($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function postConvert(Request $request): JsonResponse
    {
        $this->checkAuthToken($request, Permission::CAN_SEE);

        $currencyFrom = strtoupper($request['currencyFrom'] ?? '');
        $currencyTo = strtoupper($request['currencyTo'] ?? '');
        $date = $request['date'] ?? null;
        $companyName = $request['companyName'] ?? CurrencyDto::DEFAULT_COMPANY_NAME;
        $products = $request['products'] ?? [];

        $request->validate([
            'currencyFrom' => 'required|string|min:3|max:3|alpha',
            'currencyTo' => 'required|string|min:3|max:3|alpha',
            'date' => 'string',
            'companyName' => 'string|alpha',
            'products' => 'required|array|min:1:max:100000',
        ]);

        $data = $this->service->convert($currencyFrom, $currencyTo, $products, $date, $companyName);

        return $this->response->getResponse($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function deleteRemove(Request $request): JsonResponse
    {
        $this->checkAuthToken($request, Permission::CAN_DELETE);

        $id = $request['id'] ?? null;

        $request->validate([
            'id' => 'required|numeric|gt:0',
        ]);

        $data = $this->response->setData($this->service->remove($id));

        return $this->response->getResponse($data);
    }

    /**
     * @throws NotPermittedException
     * @throws InvalidTokenProvidedException
     */
    public function getList(Request $request): JsonResponse
    {
        $this->checkAuthToken($request, Permission::CAN_DELETE);

        $this->response = App::make(CurrencyListResponse::class);

        $data = $this->response->setData($this->service->getList());

        return $this->response->getResponse($data);
    }
}