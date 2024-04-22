<?php declare(strict_types=1);

namespace Tests\Bot\Mocks;

use Mockery\MockInterface;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;

final readonly class RemoteBotSubscriberMocker
{
    private Bot $bot;
    private Subscription $subscription;

    public function gets(Bot $bot): self
    {
        $this->bot = $bot;

        return $this;
    }

    public function returns(Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function mock(MockInterface $mock): void
    {
        $mock
            ->shouldReceive('subscribe')
            ->once()
            ->withArgs(
                fn (Bot $bot): bool => $bot->botId()->equals($this->bot->botId())
            )
            ->andReturn(
                $this->subscription
            );
    }
}
