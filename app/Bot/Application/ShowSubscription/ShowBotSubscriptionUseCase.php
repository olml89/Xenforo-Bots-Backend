<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Application\ShowSubscription;

use olml89\XenforoBots\Bot\Application\SubscriptionResult;
use olml89\XenforoBots\Bot\Domain\BotFinder;
use olml89\XenforoBots\Bot\Domain\BotNotFoundException;
use olml89\XenforoBots\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBots\Bot\Domain\Username;

final class ShowBotSubscriptionUseCase
{
    public function __construct(
        private readonly BotFinder $botFinder,
    ) {}

    /**
     * @throws InvalidUsernameException
     * @throws BotNotFoundException
     */
    public function retrieve(string $name, string $password): ?SubscriptionResult
    {
        $bot = $this->botFinder->find(new Username($name), $password);

        if (!$bot->isSubscribed()) {
            return null;
        }

        return new SubscriptionResult($bot->subscription());
    }

}
