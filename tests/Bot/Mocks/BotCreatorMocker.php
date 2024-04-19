<?php declare(strict_types=1);

namespace Tests\Bot\Mocks;

use Mockery\MockInterface;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\Password;
use olml89\XenforoBotsBackend\Bot\Domain\Username;

final readonly class BotCreatorMocker
{
    private Username $username;
    private Password $password;
    private Bot $bot;

    public function gets(Username $username, Password $password): self
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    public function returns(Bot $bot): self
    {
        $this->bot = $bot;

        return $this;
    }

    public function mock(MockInterface $mock): void
    {
        $mock
            ->shouldReceive('create')
            ->once()
            ->withArgs(
                function (Username $username, Password $password): bool {
                    return $this->username->equals($username)
                        && $this->password->equals($password);
                }
            )
            ->andReturn(
                $this->bot
            );
    }
}
