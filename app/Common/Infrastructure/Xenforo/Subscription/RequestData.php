<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Subscription;

use olml89\XenforoBotsBackend\Common\Domain\JsonSerializable;

final class RequestData extends JsonSerializable
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $password,
        public readonly string $webhook,
    ) {}
}
