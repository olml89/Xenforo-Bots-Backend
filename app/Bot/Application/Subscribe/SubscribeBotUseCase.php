<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Application\Subscribe;

use olml89\XenforoBots\Bot\Application\BotResult;
use olml89\XenforoBots\Bot\Application\SubscriptionResult;
use olml89\XenforoBots\Bot\Domain\BotFinder;
use olml89\XenforoBots\Bot\Domain\BotNotFoundException;
use olml89\XenforoBots\Bot\Domain\BotRepository;
use olml89\XenforoBots\Bot\Domain\BotStorageException;
use olml89\XenforoBots\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBots\Bot\Domain\Username;
use olml89\XenforoBots\Subscription\Domain\SubscriptionCreator;
use olml89\XenforoBots\Subscription\Domain\SubscriptionCreationException;

final class SubscribeBotUseCase
{
    public function __construct(
        private readonly BotFinder $botFinder,
        private readonly SubscriptionCreator $botSubscriber,
        private readonly BotRepository $botRepository,
    ) {}

    /**
     * @throws InvalidUsernameException
     * @throws BotNotFoundException | SubscriptionCreationException | BotStorageException
     */
    public function subscribe(string $name, string $password): BotResult
    {
        $bot = $this->botFinder->find(new Username($name), $password);
        $this->botSubscriber->subscribe($bot, $password);
        $this->botRepository->save($bot);

        return new BotResult($bot);
    }
}
