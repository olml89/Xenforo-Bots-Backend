<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application\Sync;

use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Domain\BotAuthenticator;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotFinder;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBotsBackend\Bot\Domain\Username;

final class SyncBotUseCase
{
    public function __construct(
        public readonly BotFinder $botFinder,
        public readonly BotAuthenticator $botAuthenticator,
        public readonly BotRepository $botRepository,

    ) {}

    /**
     * @throws InvalidUsernameException
     * @throws BotValidationException | BotStorageException
     */
    public function sync(string $name, string $password): BotResult
    {
        $username = new Username($name);

        if ($this->botFinder->exists($username)) {
            throw BotValidationException::alreadyExists($username);
        }

        $bot = $this->botAuthenticator->authenticate($username, $password);
        $this->botRepository->save($bot);

        return new BotResult($bot);
    }
}
