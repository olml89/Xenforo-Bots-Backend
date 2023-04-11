<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Application\UpdateSubscription;

use olml89\XenforoBots\Bot\Application\BotResult;
use olml89\XenforoBots\Bot\Application\SubscriptionResult;
use olml89\XenforoBots\Bot\Domain\BotFinder;
use olml89\XenforoBots\Bot\Domain\BotNotFoundException;
use olml89\XenforoBots\Bot\Domain\BotRepository;
use olml89\XenforoBots\Bot\Domain\BotStorageException;
use olml89\XenforoBots\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBots\Bot\Domain\Username;
use olml89\XenforoBots\Subscription\Domain\SubscriptionRetrievalException;
use olml89\XenforoBots\Subscription\Domain\SubscriptionRetriever;

final class UpdateBotSubscriptionUseCase
{
    public function __construct(
        private readonly BotFinder $botFinder,
        private readonly SubscriptionRetriever $botSubscriptionRetriever,
        private readonly BotRepository $botRepository,
    ) {}

    /**
     * @throws InvalidUsernameException
     * @throws BotNotFoundException | SubscriptionRetrievalException | BotStorageException
     */
    public function update(string $name, string $password): ?BotResult
    {
        $bot = $this->botFinder->find(new Username($name), $password);
        $updatedSubscription = $this->botSubscriptionRetriever->get($bot);
        $bot->cancelSubscription();
        $this->botRepository->save($bot);

        if (is_null($updatedSubscription)) {
            return null;
        }

        $bot->subscribe($updatedSubscription);
        $this->botRepository->save($bot);

        return new BotResult($bot);
    }
}
