<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Constants\Permission;
use Library\Responses\Common\SuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use App\Models\Dto\Currency\CurrencyDto;
use App\Http\Responses\Currency\CurrencyRateResponse;
use App\Services\Currency\CurrencyService;
use Illuminate\Http\Request;
use Throwable;

/**
 * @class CurrencyRateController
 * @package App\Http\Controllers
 */
class CurrencyRateController extends Controller
{
    /**
     * @var CurrencyService;
     */
    protected CurrencyService $service;

    /**
     * @var CurrencyRateResponse|SuccessResponse
     */
    protected CurrencyRateResponse|SuccessResponse $response;

    /**
     * @class CurrencyRateController constructor
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', [
            'except' => [
                'getCurrentRate',
                'getRateByDate'
            ]
        ]);

        $this->service = App::make(CurrencyService::class);
        $this->response = App::make(CurrencyRateResponse::class);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function getCurrentRate(Request $request): JsonResponse
    {
        $code = strtoupper($request['code'] ?? '');
        $companyName = $request['companyName'] ?? CurrencyDto::DEFAULT_COMPANY_NAME;

        $request->validate([
            'code' => 'required|string|min:3|max:3|alpha',
        ]);

        $data = $this->response->setData($this->service->getCurrentRate(strtoupper($code), $companyName));

        return $this->response->getResponse($data);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function getRateByDate(Request $request): JsonResponse
    {
        $code = strtoupper($request['code'] ?? '');
        $date = $request['date'] ?? null;
        $companyName = $request['companyName'] ?? CurrencyDto::DEFAULT_COMPANY_NAME;

        $request->validate([
            'code' => 'required|string|min:3|max:3|alpha',
            'date' => 'required|string|date_format:Y-m-d',
        ]);

        $data = $this->response->setData($this->service->getRateByDay($code, $date, $companyName));

        return $this->response->getResponse($data);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function putSetRate(Request $request): JsonResponse
    {
        $this->checkAuthToken($request, Permission::CAN_SET);

        $code = strtoupper($request['code'] ?? '');
        $rate = $request['rate'] ?? null;
        $companyName = $request['companyName'] ?? CurrencyDto::DEFAULT_COMPANY_NAME;

        $request->validate([
            'code' => 'required|string|min:3|max:3|alpha',
            'rate' => 'required|numeric|gt:0',
        ]);

        $this->response = App::make(SuccessResponse::class);

        $data = $this->response->setData($this->service->setRate($code, $rate, $companyName));

        return $this->response->getResponse($data);
    }
}