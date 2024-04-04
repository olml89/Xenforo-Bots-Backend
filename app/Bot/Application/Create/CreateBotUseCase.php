<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Bot\Application\Create;

use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreator;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotStorageException;

final class CreateBotUseCase
{
    public function __construct(
        private readonly BotCreator $botCreator,
        private readonly BotRepository $botRepository,
    ) {}

    /**
     * @throws BotCreationException | BotStorageException
     */
    public function create(string $name, string $password): BotResult
    {
        $bot = $this->botCreator->create($name, $password);
        $this->botRepository->save($bot);

        return new BotResult($bot);
    }
}
