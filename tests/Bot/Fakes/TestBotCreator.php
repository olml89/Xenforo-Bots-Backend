<?php declare(strict_types=1);

namespace Tests\Bot\Fakes;

use Database\Factories\BotFactory;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreator;
use olml89\XenforoBotsBackend\Bot\Domain\Password;
use olml89\XenforoBotsBackend\Bot\Domain\Username;

final class TestBotCreator implements BotCreator
{
    public function __construct(
        private readonly BotFactory $botFactory,
    ) {}

    public function create(Username $username, Password $password): Bot
    {
        return $this
            ->botFactory
            ->username($username)
            ->create();
    }
}
