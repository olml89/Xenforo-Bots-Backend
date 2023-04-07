<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Xenforo\User;

use olml89\XenforoBots\Common\Domain\JsonSerializable;

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
