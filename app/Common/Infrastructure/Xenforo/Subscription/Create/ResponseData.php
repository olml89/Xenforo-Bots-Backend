<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Xenforo\Subscription\Create;

use olml89\XenforoBots\Common\Infrastructure\Xenforo\ApiResponseData;
use Psr\Http\Message\ResponseInterface;

final class ResponseData extends ApiResponseData
{
    public int $a;
    private function __construct(
        public readonly string $id,
        public readonly int $subscribed_at,
    ) {}

    public static function fromResponse(ResponseInterface $response): self
    {
        $json = self::jsonDecode($response);

        return new self(
            id: $json['id'],
            subscribed_at: $json['subscribed_at'],
        );
    }
}
