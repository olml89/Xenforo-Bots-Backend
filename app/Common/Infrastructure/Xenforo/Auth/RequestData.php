<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Xenforo\Auth;

use olml89\XenforoBots\Common\Domain\JsonSerializable;

final class RequestData extends JsonSerializable
{
    public function __construct(
        public readonly string $login,
        public readonly string $password,
    ) {}
}
