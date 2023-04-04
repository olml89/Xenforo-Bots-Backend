<?php declare(strict_types=1);

namespace olml89\XenforoBots\Bot\Application\Create;

use olml89\XenforoBots\Bot\Domain\BotCreationException;
use olml89\XenforoBots\Bot\Domain\BotCreator;
use olml89\XenforoBots\Bot\Domain\BotRepository;
use olml89\XenforoBots\Bot\Domain\BotStorageException;

final class CreateBotUseCase
{
    public function __construct(
        private readonly BotCreator $botCreator,
        private readonly BotRepository $botRepository,
    ) {}

    /**
     * @throws BotCreationException | BotStorageException
     */
    public function create(string $name, string $password): CreateBotResult
    {
        $bot = $this->botCreator->create($name, $password);
        $this->botRepository->save($bot);

        return new CreateBotResult($bot);
    }
}
