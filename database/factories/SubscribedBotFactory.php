<?php declare(strict_types=1);

namespace Database\Factories;

use olml89\XenforoBotsBackend\Bot\Domain\Bot;

final readonly class SubscribedBotFactory
{
    public function __construct(
        private BotFactory $botFactory,
        private SubscriptionFactory $subscriptionFactory,
    ) {}

    public function create(): Bot
    {
        $bot = $this->botFactory->create();

        $subscription = $this
            ->subscriptionFactory
            ->bot($bot)
            ->create();

        return $bot->subscribe($subscription);
    }
}
