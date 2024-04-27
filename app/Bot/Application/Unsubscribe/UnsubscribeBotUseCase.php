<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application\Unsubscribe;

use olml89\XenforoBotsBackend\Bot\Domain\BotFinder;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\BotUnsubscriber;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionRemovalException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final readonly class UnsubscribeBotUseCase
{
    public function __construct(
        private BotFinder $botFinder,
        private BotUnsubscriber $botUnsubscriber,
    ) {}

    /**
     * @throws BotValidationException
     * @throws BotNotFoundException
     * @throws SubscriptionRemovalException
     * @throws BotStorageException
     */
    public function unsubscribe(string $username): void
    {
        try {
            $username = Username::create($username);
            $bot = $this->botFinder->findByUsername($username);
            $this->botUnsubscriber->unsubscribe($bot);
        }
        catch (ValueObjectException $e) {
            throw BotValidationException::fromException($e);
        }
    }
}
