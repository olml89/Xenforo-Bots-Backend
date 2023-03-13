<?php declare(strict_types=1);

namespace olml89\XenforoBots\Infrastructure\Xenforo\BotCreator;

use olml89\XenforoBots\Domain\JsonSerializable;

final class CreateUserRequestData extends JsonSerializable
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
