<?php declare(strict_types=1);

namespace Library\Responses\Common;

use Library\Responses\AbstractResponse;

/**
 * @class SuccessResponse
 * @package Library\Responses\Common
 */
class SuccessResponse extends AbstractResponse
{
    /**
     * @var bool $isSuccess
     */
    public bool $isSuccess;

    /**
     * @param bool $result
     * @return self
     */
    public function setData(bool $result): self
    {
        $this->isSuccess = $result;

        return $this;
    }
}