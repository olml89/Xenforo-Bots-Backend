<?php declare(strict_types=1);

namespace olml89\XenforoBots\Application\UseCases\Bot\Create;

use olml89\XenforoBots\Domain\Bot\BotCreationException;
use olml89\XenforoBots\Domain\Bot\BotCreator;

final class CreateBot
{
    public function __construct(
        private readonly BotCreator $botCreator,
    ) {}

    /**
     * @throws BotCreationException
     */
    public function create(string $name, string $password): CreateBotResult
    {
        $bot = $this->botCreator->create($name, $password);

        return new CreateBotResult($bot);
    }
}
