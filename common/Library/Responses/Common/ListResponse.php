<?php declare(strict_types=1);

namespace Library\Responses\Common;

use Library\Responses\AbstractResponse;

/**
 * @class ListResponse
 * @package Library\Responses\Common
 */
class ListResponse extends AbstractResponse
{
    /**
     * @var array $items
     */
    public array $items;

    /**
     * @param array $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->items = $data;
    }
}