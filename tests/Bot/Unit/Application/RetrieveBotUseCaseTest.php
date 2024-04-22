<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Application;

use Database\Factories\SubscribedBotFactory;
use Database\Factories\ValueObjects\UsernameFactory;
use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Application\Retrieve\RetrieveBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;
use Tests\Bot\Fakes\InMemoryBotRepository;
use Tests\TestCase;

final class RetrieveBotUseCaseTest extends TestCase
{
    private readonly UsernameFactory $usernameFactory;
    private readonly SubscribedBotFactory $subscribedBotFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usernameFactory = $this->resolve(UsernameFactory::class);
        $this->subscribedBotFactory = $this->resolve(SubscribedBotFactory::class);
    }

    public function testItThrowsBotValidationExceptionIfInvalidUsernameIsProvided(): void
    {
        $invalidUsername = '';

        $this->expectExceptionObject(
            new BotValidationException(InvalidUsernameException::empty())
        );

        $this
            ->resolve(RetrieveBotUseCase::class)
            ->retrieve($invalidUsername);
    }

    public function testItThrowsBotNotFoundExceptionIfABotWithAProvidedUsernameDoesNotExist(): void
    {
        $username = $this->usernameFactory->create();

        $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository()
        );

        $this->expectExceptionObject(
            BotNotFoundException::username($username)
        );

        $this
            ->resolve(RetrieveBotUseCase::class)
            ->retrieve((string)$username);
    }

    public function testItRetrievesABot(): void
    {
        $bot = $this->subscribedBotFactory->create();

        $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository($bot)
        );

        $botResult = $this
            ->resolve(RetrieveBotUseCase::class)
            ->retrieve((string)$bot->username());

        $this->assertEquals(
            new BotResult($bot),
            $botResult
        );
    }
}
