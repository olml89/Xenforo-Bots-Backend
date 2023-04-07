<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Xenforo\Subscription;

use olml89\XenforoBots\Common\Infrastructure\Xenforo\ApiResponseData;
use Psr\Http\Message\ResponseInterface;

final class ResponseData extends ApiResponseData
{
    private function __construct(
        public readonly string $id,
        public readonly int $subscribed_at,
    ) {}

    public static function fromResponse(ResponseInterface $response): self
    {
        $json = self::jsonDecode($response);
        $subscription = $json['subscription'];

        return new self(
            id: $subscription['subscription_id'],
            subscribed_at: $subscription['subscribed_at'],
        );
    }
}
