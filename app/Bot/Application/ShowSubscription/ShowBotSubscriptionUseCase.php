<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application\ShowSubscription;

use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Domain\BotFinder;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBotsBackend\Bot\Domain\Username;

final class ShowBotSubscriptionUseCase
{
    public function __construct(
        private readonly BotFinder $botFinder,
    ) {}

    /**
     * @throws InvalidUsernameException
     * @throws BotNotFoundException
     */
    public function retrieve(string $name, string $password): ?BotResult
    {
        $bot = $this->botFinder->find(new Username($name), $password);

        if (!$bot->isSubscribed()) {
            return null;
        }

        return new BotResult($bot);
    }

}
