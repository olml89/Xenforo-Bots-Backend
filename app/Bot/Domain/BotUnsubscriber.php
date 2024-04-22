<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Domain;

use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionRemovalException;

final readonly class BotUnsubscriber
{
    public function __construct(
        private RemoteBotUnsubscriber $remoteBotUnsubscriber,
        private BotRepository $botRepository,
    ) {}

    /**
     * @throws SubscriptionRemovalException
     * @throws BotStorageException
     */
    public function unsubscribe(Bot $bot): void
    {
        $this->remoteBotUnsubscriber->unsubscribe($bot);
        $this->botRepository->delete($bot);
    }
}
