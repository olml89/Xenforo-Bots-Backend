<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application\Subscribe;

use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Domain\BotFinder;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionCreator;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionCreationException;

final class SubscribeBotUseCase
{
    public function __construct(
        private readonly BotFinder $botFinder,
        private readonly SubscriptionCreator $botSubscriptionCreator,
        private readonly BotRepository $botRepository,
    ) {}

    /**
     * @throws InvalidUsernameException
     * @throws BotNotFoundException | SubscriptionCreationException | BotStorageException
     */
    public function subscribe(string $name, string $password): BotResult
    {
        $bot = $this->botFinder->find(new Username($name), $password);

        if ($bot->isSubscribed()) {
            throw SubscriptionCreationException::alreadySubscribed($bot);
        }

        $subscription = $this->botSubscriptionCreator->create($bot, $password);
        $bot->subscribe($subscription);
        $this->botRepository->save($bot);

        return new BotResult($bot);
    }
}
