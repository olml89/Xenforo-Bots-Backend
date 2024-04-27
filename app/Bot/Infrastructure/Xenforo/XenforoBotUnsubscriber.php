<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\RemoteBotUnsubscriber;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionRemovalException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumer;

final readonly class XenforoBotUnsubscriber implements RemoteBotUnsubscriber
{
    public function __construct(
        private XenforoApiConsumer $xenforoApiConsumer,
    ) {}

    /**
     * @throws SubscriptionRemovalException
     */
    public function unsubscribe(Bot $bot): void
    {
        try {
            $this->xenforoApiConsumer->delete(
                endpoint: sprintf(
                    'bots/%s/subscriptions/%s',
                    $bot->botId(),
                    $bot->subscription()->subscriptionId(),
                ),
                headers: [
                    'XF-Api-Key' => (string)$bot->apiKey(),
                ],
            );
        }
        catch (XenforoApiException $e) {
            throw SubscriptionRemovalException::fromException($e);
        }
    }
}
