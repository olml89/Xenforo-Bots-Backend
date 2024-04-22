<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotActivationException;
use olml89\XenforoBotsBackend\Bot\Domain\RemoteBotActivator;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumer;

final readonly class XenforoBotActivator implements RemoteBotActivator
{
    public function __construct(
        private XenforoApiConsumer $xenforoApiConsumer,
    ) {}

    /**
     * @throws BotActivationException
     */
    public function activate(Bot $bot): void
    {
        try {
            $this->xenforoApiConsumer->post(
                endpoint: sprintf(
                    'bots/%s/subscriptions/%s/activation',
                    $bot->botId(),
                    $bot->subscription()->subscriptionId()
                ),
                headers: [
                    'XF-Api-Key' => (string)$bot->apiKey(),
                ],
            );
        }
        catch (XenforoApiException $e) {
            throw new BotActivationException($e);
        }
    }
}
