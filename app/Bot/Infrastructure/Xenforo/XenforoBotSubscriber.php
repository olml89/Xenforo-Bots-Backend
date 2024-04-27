<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotSubscriber;
use olml89\XenforoBotsBackend\Bot\Domain\RemoteBotSubscriber;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionCreationException;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionValidationException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiUnprocessableEntityException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumer;

final readonly class XenforoBotSubscriber implements RemoteBotSubscriber
{
    public function __construct(
        private XenforoApiConsumer $xenforoApiConsumer,
        private Url $backendUrl,
        private ApiKey $backendApiKey,
    ) {}

    /**
     * @throws SubscriptionValidationException
     * @throws SubscriptionCreationException
     */
    public function subscribe(Bot $bot): Subscription
    {
        try {
            $xenforoBotSubscriptionCreationData = new XenforoBotSubscriptionCreationData(
                webhook: (string)$this->backendUrl,
                platform_api_key: (string)$this->backendApiKey
            );

            $xenforoBotSubscriptionData = XenforoBotSubscriptionData::fromResponse(
                $this->xenforoApiConsumer->post(
                    endpoint: sprintf('bots/%s/subscriptions', $bot->botId()),
                    data: $xenforoBotSubscriptionCreationData,
                    headers: [
                        'XF-Api-Key' => (string)$bot->apiKey(),
                    ]
                )
            );

            return new Subscription(
                subscriptionId: Uuid::create($xenforoBotSubscriptionData->bot_subscription_id),
                subscribedAt: UnixTimestamp::create($xenforoBotSubscriptionData->subscribed_at),
                bot: $bot
            );
        }
        catch (ValueObjectException|XenforoApiUnprocessableEntityException $e) {
            throw SubscriptionValidationException::fromException($e);
        }
        catch (XenforoApiException $e) {
            throw SubscriptionCreationException::fromException($e);
        }
    }
}
