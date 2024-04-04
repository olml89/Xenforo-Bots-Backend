<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application\CancelSubscription;

use olml89\XenforoBotsBackend\Bot\Domain\BotFinder;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionRemovalException;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionRemover;

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

        if (!$bot->isSubscribed()) {
            throw SubscriptionRemovalException::notSubscribed($bot);
        }

        $this->subscriptionRemover->remove($bot);
        $bot->cancelSubscription();
        $this->botRepository->save($bot);
    }
}
