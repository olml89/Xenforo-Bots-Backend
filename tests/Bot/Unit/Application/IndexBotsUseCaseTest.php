<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Application;

use Database\Factories\SubscribedBotFactory;
use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Application\Index\IndexBotsUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use Tests\Bot\Fakes\InMemoryBotRepository;
use Tests\TestCase;

final class IndexBotsUseCaseTest extends TestCase
{
    public function testItReturnsEmptyBotResultsIfThereAreNoBots(): void
    {
        $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository()
        );

        $botResults = $this
            ->resolve(IndexBotsUseCase::class)
            ->index();

        $this->assertEmpty($botResults);
    }

    public function testItReturnsBotResults(): void
    {
        $bot = $this->resolve(SubscribedBotFactory::class)->create();

        $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository($bot)
        );

        $botResults = $this
            ->resolve(IndexBotsUseCase::class)
            ->index();

        $this->assertCount(
            1,
            $botResults
        );
        $this->assertEquals(
            new BotResult($bot),
            $botResults[0]
        );
    }
}
