<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotDeactivationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotDeactivator;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\XenforoApiConsumer;

final readonly class XenforoBotDeactivator implements BotDeactivator
{
    public function __construct(
        private XenforoApiConsumer $xenforoApiConsumer,
    ) {}

    public function deactivate(Bot $bot): void
    {
        try {
            $this->xenforoApiConsumer->delete(
                endpoint: sprintf(
                    'bots/%s/subscriptions/%s/activation',
                    $bot->botId(),
                    $bot->subscription()->subscriptionId(),
                ),
                headers: [
                    'XF-Api-Key' => (string)$bot->apiKey(),
                ],
            );
        }
        catch (XenforoApiException $e) {
            throw new BotDeactivationException($e);
        }
    }
}
