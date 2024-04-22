<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application\Deactivate;

use olml89\XenforoBotsBackend\Bot\Domain\BotDeactivationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotDeactivator;
use olml89\XenforoBotsBackend\Bot\Domain\RemoteBotDeactivator;
use olml89\XenforoBotsBackend\Bot\Domain\BotFinder;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ValueObjectException;

final readonly class DeactivateBotUseCase
{
    public function __construct(
        private BotFinder $botFinder,
        private BotDeactivator $botDeactivator,
    ) {}

    /**
     * @throws BotValidationException
     * @throws BotNotFoundException
     * @throws BotDeactivationException
     * @throws BotStorageException
     */
    public function deactivate(string $username): void
    {
        try {
            $username = Username::create($username);
            $bot = $this->botFinder->findByUsername($username);
            $this->botDeactivator->deactivate($bot);
        }
        catch (ValueObjectException $e) {
            throw new BotValidationException($e);
        }
    }
}
