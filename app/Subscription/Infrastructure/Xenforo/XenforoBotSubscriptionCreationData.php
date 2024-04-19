<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Infrastructure\Xenforo;

use olml89\XenforoBotsBackend\Common\Domain\JsonSerializable;

final class XenforoBotSubscriptionCreationData extends JsonSerializable
{
    public function __construct(
        public string $webhook,
        public string $platform_api_key,
    ) {}
}
