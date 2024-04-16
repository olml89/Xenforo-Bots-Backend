<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\Create;

use Database\Factories\BotFactory;
use Database\Factories\ValueObjects\PasswordFactory;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotCreationData;

final class XenforoBotCreationDataCreator
{
    private ?string $username = null;
    private ?string $password = null;
    private ?Bot $bot;

    public function __construct(
        private readonly BotFactory $botFactory,
        private readonly PasswordFactory $passwordFactory,
    ) {}

    public function username(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function password(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function bot(Bot $bot): self
    {
        $this->username = (string)$bot->username();

        return $this;
    }

    public function reset(): void
    {
        $this->username = null;
        $this->password = null;
        $this->bot = null;
    }

    public function create(): XenforoBotCreationData
    {
        $this->bot ??= $this->botFactory->create();

        $xenforoBotCreationData = new XenforoBotCreationData(
            username: $this->username ?? (string)$this->bot->username(),
            password: $this->password ?? (string)$this->passwordFactory->create()
        );
        $this->reset();

        return $xenforoBotCreationData;
    }
}
