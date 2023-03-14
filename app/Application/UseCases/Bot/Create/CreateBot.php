<?php declare(strict_types=1);

namespace olml89\XenforoBots\Application\UseCases\Bot\Create;

use olml89\XenforoBots\Domain\Bot\BotCreationException;
use olml89\XenforoBots\Domain\Bot\BotStorageException;
use olml89\XenforoBots\Domain\Bot\BotCreator;
use olml89\XenforoBots\Domain\Bot\BotRepository;

final class CreateBot
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
