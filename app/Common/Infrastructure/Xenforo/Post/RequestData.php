<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Xenforo\Post;

use olml89\XenforoBots\Common\Domain\JsonSerializable;

final class RequestData extends JsonSerializable
{
    public function __construct(
        public readonly int $thread_id,
        public readonly string $message,
    ) {}
}
