<?php declare(strict_types=1);

namespace Tests\Bot\Integration;

use Database\Factories\SubscribedBotFactory;
use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use Symfony\Component\Console\Exception\RuntimeException;
use Tests\Helpers\DoctrineTransactions;
use Tests\Helpers\ExecutesDoctrineTransactions;
use Tests\TestCase;

final class RetrieveBotCommandTest extends TestCase implements ExecutesDoctrineTransactions
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

    public function testItThrowsRuntimeExceptionIfUsernameIsEmpty(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "username").');

        $this
            ->artisan('bot:retrieve')
            ->assertFailed();
    }

    public function testItRetrievesABotAndPrintsABotResult(): void
    {
        $bot = $this->subscribedBotFactory->create();
        $this->botRepository->save($bot);

        $expectedBotResult = new BotResult($bot);

        $this
            ->artisan('bot:retrieve', [
                'username' => (string)$bot->username(),
            ])
            ->assertSuccessful()
            ->expectsOutputToContain(
                (string)$expectedBotResult
            );
    }
}
