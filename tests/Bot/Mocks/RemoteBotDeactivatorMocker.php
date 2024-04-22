<?php declare(strict_types=1);

namespace Tests\Bot\Mocks;

use Mockery\MockInterface;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;

final class RemoteBotDeactivatorMocker
{
    private Bot $bot;

    public function gets(Bot $bot): self
    {
        $this->bot = $bot;

        return $this;
    }

    public function mock(MockInterface $mock): void
    {
        $mock
            ->shouldReceive('deactivate')
            ->once()
            ->withArgs(
                fn (Bot $bot): bool => $bot->botId()->equals($this->bot->botId())
            )
            ->andReturn();

        $this->bot->deactivate();
    }
}
