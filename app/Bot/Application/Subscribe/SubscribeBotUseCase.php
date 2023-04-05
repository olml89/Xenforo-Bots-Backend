<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Application\Subscribe;

use olml89\XenforoBots\Bot\Domain\BotFinder;
use olml89\XenforoBots\Bot\Domain\BotNotFoundException;
use olml89\XenforoBots\Bot\Domain\BotRepository;
use olml89\XenforoBots\Bot\Domain\BotStorageException;
use olml89\XenforoBots\Bot\Domain\BotSubscriber;
use olml89\XenforoBots\Bot\Domain\BotSubscriptionException;
use olml89\XenforoBots\Bot\Domain\Username;

final class SubscribeBotUseCase
{
    public function __construct(
        private readonly BotFinder $botFinder,
        private readonly BotSubscriber $botSubscriber,
        private readonly BotRepository $botRepository,
    ) {}

    /**
     * @throws BotNotFoundException | BotSubscriptionException | BotStorageException
     */
    public function subscribe(string $name, string $password): SubscribeBotResult
    {
        $bot = $this->botFinder->find(new Username($name), $password);
        $subscription = $this->botSubscriber->subscribe($bot, $password);
        $bot->subscribe($subscription);
        $this->botRepository->save($bot);

        return new SubscribeBotResult($subscription);
    }
}
