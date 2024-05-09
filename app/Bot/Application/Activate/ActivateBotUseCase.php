<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application\Activate;

use olml89\XenforoBotsBackend\Bot\Domain\BotActivationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotActivator;
use olml89\XenforoBotsBackend\Bot\Domain\BotFinder;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\EqualsUsernameSpecification;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final readonly class ActivateBotUseCase
{
    public function __construct(
        private BotFinder $botFinder,
        private BotActivator $botActivator,
    ) {}

    /**
     * @throws BotValidationException
     * @throws BotNotFoundException
     * @throws BotActivationException
     * @throws BotStorageException
     */
    public function activate(string $username): void
    {
        try {
            $username = Username::create($username);

            $bot = $this
                ->botFinder
                ->findBy(new EqualsUsernameSpecification($username));

            $this->botActivator->activate($bot);
        }
        catch (ValueObjectException $e) {
            throw BotValidationException::fromException($e);
        }
    }
}
