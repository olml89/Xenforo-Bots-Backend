<?php declare(strict_types=1);

namespace olml89\XenforoBots\Subscription\Infrastructure\SubscriptionRetriever;

use Illuminate\Foundation\Application;
use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBots\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBots\Common\Domain\ValueObjects\Uuid\UuidManager;
use olml89\XenforoBots\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\XenforoApi;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\XenforoApiException;
use olml89\XenforoBots\Subscription\Domain\Subscription;
use olml89\XenforoBots\Subscription\Domain\SubscriptionRetrievalException;
use olml89\XenforoBots\Subscription\Domain\SubscriptionRetriever;

final class XenforoSubscriptionRetriever implements SubscriptionRetriever
{
    private readonly Url $appUrl;

    public function __construct(
        private readonly XenforoApi $xenforoApi,
        private readonly UuidManager $uuidManager,
        Application $application
    ) {
        $this->appUrl = $application[Url::class];
    }

    /**
     * @throws SubscriptionRetrievalException
     */
    public function get(Bot $bot): ?Subscription
    {
        try {
            $subscriptionResponseData = $this->xenforoApi->getSubscription(
                user_id: $bot->userId()->toInt(),
                webhook: $this->appUrl->urlencode(),
            );

            if (is_null($subscriptionResponseData)) {
                return null;
            }

            return new Subscription(
                id: new Uuid($subscriptionResponseData->id, $this->uuidManager),
                bot: $bot,
                xenforoUrl: $this->xenforoApi->apiUrl(),
                subscribedAt: UnixTimestamp::toDateTimeImmutable($subscriptionResponseData->subscribed_at),
            );
        }
        catch (XenforoApiException|ValueObjectException $e) {
            throw new SubscriptionRetrievalException($e->getMessage(), $e);
        }
    }
}
