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

final class ActivateBotCommandTest extends TestCase implements ExecutesDoctrineTransactions, InteractsWithXenforoApi
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
            ->artisan('bot:activate')
            ->assertFailed();
    }

    public function testItActivatesAXenforoBot(): void
    {
        $bot = $this->subscribedBotFactory->create();
        $bot->deactivate();
        $this->botRepository->save($bot);

        $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($bot->subscription())
            ->activate()
            ->ok();

        $this
            ->artisan('bot:activate', [
                'username' => (string)$bot->username(),
            ])
            ->assertSuccessful();

        $this->assertTrue(
            $this
                ->botRepository
                ->get($bot->botId())
                ->isActive()
        );
        $this->assertDatabaseHas(
            'subscriptions',
            [
                'subscription_id' => (string)$bot->subscription()->subscriptionId(),
                'is_active' => true,
            ]
        );
    }
}
