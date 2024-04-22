<?php declare(strict_types=1);

namespace Tests\Bot\Integration;

use Database\Factories\SubscribedBotFactory;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use Symfony\Component\Console\Exception\RuntimeException;
use Tests\Helpers\DoctrineTransactions;
use Tests\Helpers\ExecutesDoctrineTransactions;
use Tests\Helpers\XenforoApi\InteractsWithXenforoApi;
use Tests\Helpers\XenforoApi\XenforoApi;
use Tests\TestCase;

final class UnsubscribeBotCommandTest extends TestCase implements ExecutesDoctrineTransactions, InteractsWithXenforoApi
{
    use DoctrineTransactions;
    use XenforoApi;

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
            ->artisan('bot:unsubscribe')
            ->assertFailed();
    }

    public function testItUnsubscribesAXenforoBot(): void
    {
        $bot = $this->subscribedBotFactory->create();
        $this->botRepository->save($bot);

        $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($bot->subscription())
            ->delete()
            ->ok();

        $this
            ->artisan('bot:unsubscribe', [
                'username' => (string)$bot->username(),
            ])
            ->assertSuccessful();

        $this->assertDatabaseCount(
            'bots',
            0
        );
    }
}
