<?php declare(strict_types=1);

namespace Tests\Bot\Integration;

use Database\Factories\SubscribedBotFactory;
use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use Tests\Helpers\DoctrineTransactions;
use Tests\Helpers\ExecutesDoctrineTransactions;
use Tests\TestCase;

final class IndexBotsCommandTest extends TestCase implements ExecutesDoctrineTransactions
{
    use DoctrineTransactions;

    private readonly SubscribedBotFactory $subscribedBotFactory;
    private readonly BotRepository $botRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subscribedBotFactory = $this->resolve(SubscribedBotFactory::class);
        $this->botRepository = $this->resolve(BotRepository::class);
    }

    public function testItPrintsNoBotsInBackendMessageIfThereAreNoBots(): void
    {
        $this
            ->artisan('bot:index')
            ->assertSuccessful()
            ->expectsOutputToContain(sprintf(
                'There are no Bots in this backend%s',
                PHP_EOL,
            ));
    }

    public function testItPrintsABotResultsList(): void
    {
        $bot = $this->subscribedBotFactory->create();
        $this->botRepository->save($bot);

        $expectedBotResult = new BotResult($bot);

        $this
            ->artisan('bot:index')
            ->assertSuccessful()
            ->expectsOutputToContain(
                (string)$expectedBotResult
            );
    }
}
