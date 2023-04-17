<?php declare(strict_types=1);

namespace Library\Responses;

use Library\Traits\Common\PropertyLoadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

/**
 * @class AbstractResponse
 * @package Library\Responses
 */
abstract class AbstractResponse
{
    use PropertyLoadTrait;

    /**
     * @param array|object $data
     * @return JsonResponse
     */
    public function getResponse(array|object $data): JsonResponse
    {
        /** @var JsonResponse $response */
        $response = App::make(JsonResponse::class, [
            'data' => is_object($data) ? (array)$data : $data
        ]);

        return $response->setData($data);
    }
}