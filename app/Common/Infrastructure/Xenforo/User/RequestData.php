<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\User;

use olml89\XenforoBotsBackend\Common\Domain\JsonSerializable;

final class RequestData extends JsonSerializable
{
    public readonly int $api_bypass_permissions;

    public function __construct(
        public readonly string $username,
        public readonly string $password,
    )
    {
        $this->api_bypass_permissions = 1;
    }
}
