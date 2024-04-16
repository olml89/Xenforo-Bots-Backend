<?php declare(strict_types=1);

namespace Tests\Bot\Integration;

use Database\Factories\BotFactory;
use Illuminate\Support\Facades\Artisan;
use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use Symfony\Component\Console\Exception\RuntimeException;
use Tests\Helpers\DoctrineTransactions;
use Tests\Helpers\ExecutesDoctrineTransactions;
use Tests\Helpers\XenforoApi\InteractsWithXenforoApi;
use Tests\Helpers\XenforoApi\XenforoApi;
use Tests\TestCase;

final class CreateBotCommandTest extends TestCase implements ExecutesDoctrineTransactions, InteractsWithXenforoApi
{
    use DoctrineTransactions;
    use XenforoApi;

    private readonly BotFactory $botFactory;

    private const string CREATED_USER_OUTPUT_FORMAT =
        '"bot_id": "%s",'
        ."\n".'    "api_key": "%s",'
        ."\n".'    "username": "%s",'
        ."\n".'    "registered_at": "%s",'
        ."\n".'    "subscription": null';

    protected function setUp(): void
    {
        parent::setUp();

        $this->botFactory = $this->resolve(BotFactory::class);
    }

    public function testItThrowsRuntimeExceptionIfUsernameAndPasswordAreEmpty(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "username, password").');

        $this
            ->artisan('bot:create')
            ->assertFailed();
    }

    public function testItThrowsRuntimeExceptionIfPasswordIsEmpty(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "password").');

        $this
            ->artisan('bot:create', [
                'username' => fake()->userName(),
            ])
            ->assertFailed();
    }

    public function testItCreatesNewXenforoBotAndPrintsABotResult(): void
    {
        $bot = $this->botFactory->create();
        $password = fake()->password();
        $expectedBotResult = new BotResult($bot);

        $this
            ->xenforoApiResponseSimulator
            ->bots($bot)
            ->create()
            ->ok();

        $this
            ->artisan('bot:create', [
                'username' => (string)$bot->username(),
                'password' => $password,
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
