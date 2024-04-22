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

final class DeactivateBotCommandTest extends TestCase implements ExecutesDoctrineTransactions, InteractsWithXenforoApi
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
            ->artisan('bot:deactivate')
            ->assertFailed();
    }

    public function testItDeactivatesAXenforoBot(): void
    {
        $bot = $this->subscribedBotFactory->create();
        $bot->activate();
        $this->botRepository->save($bot);

        $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($bot->subscription())
            ->deactivate()
            ->ok();

        $this
            ->artisan('bot:deactivate', [
                'username' => (string)$bot->username(),
            ])
            ->assertSuccessful();

        $this->assertFalse(
            $this
                ->botRepository
                ->get($bot->botId())
                ->isActive()
        );
        $this->assertDatabaseHas(
            'subscriptions',
            [
                'subscription_id' => (string)$bot->subscription()->subscriptionId(),
                'is_active' => false,
            ]
        );
    }
}
