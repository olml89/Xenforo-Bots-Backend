<?php declare(strict_types=1);

namespace Tests\Bot\Integration;

use Database\Factories\BotFactory;
use Database\Factories\SubscriptionFactory;
use Database\Factories\ValueObjects\PasswordFactory;
use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use Symfony\Component\Console\Exception\RuntimeException;
use Tests\Helpers\DoctrineTransactions;
use Tests\Helpers\ExecutesDoctrineTransactions;
use Tests\Helpers\XenforoApi\InteractsWithXenforoApi;
use Tests\Helpers\XenforoApi\XenforoApi;
use Tests\TestCase;

final class SubscribeBotCommandTest extends TestCase implements ExecutesDoctrineTransactions, InteractsWithXenforoApi
{
    use DoctrineTransactions;
    use XenforoApi;

    private readonly BotFactory $botFactory;
    private readonly SubscriptionFactory $subscriptionFactory;
    private readonly PasswordFactory $passwordFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->botFactory = $this->resolve(BotFactory::class);
        $this->subscriptionFactory = $this->resolve(SubscriptionFactory::class);
        $this->passwordFactory = $this->resolve(PasswordFactory::class);
    }

    public function testItThrowsRuntimeExceptionIfUsernameAndPasswordAreEmpty(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "username, password").');

        $this
            ->artisan('bot:subscribe')
            ->assertFailed();
    }

    public function testItThrowsRuntimeExceptionIfPasswordIsEmpty(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "password").');

        $this
            ->artisan('bot:subscribe', [
                'username' => fake()->userName(),
            ])
            ->assertFailed();
    }

    public function testItCreatesNewXenforoBotAndPrintsABotResult(): void
    {
        $bot = $this
            ->botFactory
            ->create();

        $subscription = $this
            ->subscriptionFactory
            ->bot($bot)
            ->create();

        $password = $this
            ->passwordFactory
            ->create();

        $expectedBotResult = new BotResult(
            (clone $bot)->subscribe($subscription)
        );

        $this
            ->xenforoApiResponseSimulator
            ->bots($bot)
            ->create()
            ->ok();

        $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($subscription)
            ->create()
            ->ok();

        $this
            ->artisan('bot:subscribe', [
                'username' => (string)$bot->username(),
                'password' => (string)$password,
            ])
            ->assertSuccessful()
            ->expectsOutputToContain(
                (string)$expectedBotResult
            );

        $this->assertDatabaseCount(
            'bots',
            1
        );
        $this->assertDatabaseHas(
            'bots',
            $expectedBotResult->jsonSerialize()
        );
    }
}
