<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Application\Sync;

use olml89\XenforoBots\Bot\Application\BotResult;
use olml89\XenforoBots\Bot\Domain\BotAuthenticator;
use olml89\XenforoBots\Bot\Domain\BotCreationException;
use olml89\XenforoBots\Bot\Domain\BotFinder;
use olml89\XenforoBots\Bot\Domain\BotRepository;
use olml89\XenforoBots\Bot\Domain\BotStorageException;
use olml89\XenforoBots\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBots\Bot\Domain\Username;

final class SyncBotUseCase
{
    public function __construct(
        public readonly BotFinder $botFinder,
        public readonly BotAuthenticator $botAuthenticator,
        public readonly BotRepository $botRepository,

    ) {}

    /**
     * @throws InvalidUsernameException
     * @throws BotCreationException | BotStorageException
     */
    public function sync(string $name, string $password): BotResult
    {
        $username = new Username($name);

        if ($this->botFinder->exists($username)) {
            throw BotCreationException::alreadyExists($username);
        }

        $bot = $this->botAuthenticator->authenticate($username, $password);
        $this->botRepository->save($bot);

        return new BotResult($bot);
    }
}
