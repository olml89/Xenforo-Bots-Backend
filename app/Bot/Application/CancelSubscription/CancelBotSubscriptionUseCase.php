<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Application\CancelSubscription;

use olml89\XenforoBots\Bot\Domain\BotFinder;
use olml89\XenforoBots\Bot\Domain\BotNotFoundException;
use olml89\XenforoBots\Bot\Domain\BotRepository;
use olml89\XenforoBots\Bot\Domain\BotStorageException;
use olml89\XenforoBots\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBots\Bot\Domain\Username;
use olml89\XenforoBots\Subscription\Domain\SubscriptionRemovalException;
use olml89\XenforoBots\Subscription\Domain\SubscriptionRemover;

final class CancelBotSubscriptionUseCase
{
    public function __construct(
        private readonly BotFinder $botFinder,
        private readonly SubscriptionRemover $subscriptionRemover,
        private readonly BotRepository $botRepository,
    ) {}

    /**
     * @throws InvalidUsernameException
     * @throws BotNotFoundException | SubscriptionRemovalException | BotStorageException
     */
    public function cancel(string $name, string $password): void
    {
        $bot = $this->botFinder->find(new Username($name), $password);
        $this->subscriptionRemover->remove($bot);
        $bot->cancelSubscription();
        $this->botRepository->save($bot);
    }
}
