<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Subscription\Infrastructure\SubscriptionCreator;

use Illuminate\Foundation\Application;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\UuidManager;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Subscription\RequestData as SubscriptionRequestData;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApi;
use olml89\XenforoBotsBackend\Subscription\Domain\Subscription;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionCreationException;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionCreator;

final class XenforoSubscriptionCreator implements SubscriptionCreator
{
    private readonly Url $appUrl;

    public function __construct(
        private readonly XenforoApi $xenforoApi,
        private readonly UuidManager $uuidManager,
        Application $application,
    ) {
        $this->appUrl = $application[Url::class];
    }

    /**
     * @throws SubscriptionCreationException
     */
    public function create(Bot $bot, string $password): Subscription
    {
        try {
            $subscriptionRequestData = new SubscriptionRequestData(
                user_id: $bot->userId()->toInt(),
                password: $password,
                webhook: (string)$this->appUrl,
            );
            $createSubscriptionResponseData = $this->xenforoApi->postSubscription($subscriptionRequestData);

            return new Subscription(
                id: new Uuid($createSubscriptionResponseData->id, $this->uuidManager),
                bot: $bot,
                xenforoUrl: $this->xenforoApi->apiUrl(),
                subscribedAt: UnixTimestamp::toDateTimeImmutable($createSubscriptionResponseData->subscribed_at),
            );
        }
        catch (XenforoApiException|ValueObjectException $e) {
            throw new SubscriptionCreationException($e->getMessage(), $e);
        }
    }
}
