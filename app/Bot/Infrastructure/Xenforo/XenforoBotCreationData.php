<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo;

use olml89\XenforoBotsBackend\Common\Domain\JsonSerializable;

final class XenforoBotCreationData extends JsonSerializable
{
    public function __construct(
        public readonly string $username,
        public readonly string $password,
    ) {}
}
