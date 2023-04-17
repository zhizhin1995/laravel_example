<?php declare(strict_types=1);

namespace Library\Exceptions\Traits;

use Throwable;

/**
 * @class HandlerTrait
 * @package Library\Exceptions\Traits
 */
trait HandlerTrait
{
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function registerExceptions(): void
    {
        $this->renderable(function (Throwable $e) {
            $status = $e->status ?? 500;

            switch ($e::class) {
                case 'Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException':
                    $status = 401;
                    break;
                default:
                    break;
            }

            return response()->json([
                'message' => $e->getMessage()
            ], $status);
        });
    }
}