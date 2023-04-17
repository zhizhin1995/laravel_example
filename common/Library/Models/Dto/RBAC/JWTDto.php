<?php declare(strict_types=1);

namespace Library\Models\Dto\RBAC;

use Library\Models\Dto\AbstractDto;

use stdClass;

/**
 * @class JWTDto
 * @package Library\Models\Dto\RBAC
 */
class JWTDto extends AbstractDto
{
    /**
     * @var stdClass $header
     */
    public stdClass $header;

    /**
     * @var stdClass $payload
     */
    public stdClass $payload;

    /**
     * @var string $secret
     */
    public string $secret;

    /**
     * @param stdClass $header
     * @param stdClass $payload
     * @param string $secret
     */
    public function __construct(stdClass $header, stdClass $payload, string $secret)
    {
        $this->payload = $payload;
        $this->header = $header;
        $this->secret = $secret;
    }
}