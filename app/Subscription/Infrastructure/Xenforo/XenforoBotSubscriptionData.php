<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Infrastructure\Xenforo;

use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\ApiResponseData;
use Psr\Http\Message\ResponseInterface;

final readonly class XenforoBotSubscriptionData extends ApiResponseData
{
    public function __construct(
        public string $bot_subscription_id,
        public string $webhook,
        public string $platform_api_key,
        public bool $is_active,
        public int $subscribed_at,
    ) {}

    public static function fromResponse(ResponseInterface $response): self
    {
        $json = self::jsonDecode($response);
        $botSubscription = $json['botSubscription'];

        return new self(
            bot_subscription_id: $botSubscription['bot_subscription_id'],
            webhook: $botSubscription['webhook'],
            platform_api_key: $botSubscription['platform_api_key'],
            is_active: $botSubscription['is_active'],
            subscribed_at: $botSubscription['subscribed_at']
        );
    }
}
