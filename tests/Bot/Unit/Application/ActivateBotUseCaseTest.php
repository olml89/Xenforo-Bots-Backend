<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Application;

use Database\Factories\SubscribedBotFactory;
use Database\Factories\ValueObjects\UsernameFactory;
use Mockery\MockInterface;
use olml89\XenforoBotsBackend\Bot\Application\Activate\ActivateBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\RemoteBotActivator;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\InvalidUsernameException;
use Tests\Bot\Fakes\InMemoryBotRepository;
use Tests\Bot\Mocks\RemoteBotActivatorMocker;
use Tests\TestCase;

final class ActivateBotUseCaseTest extends TestCase
{
    private readonly UsernameFactory $usernameFactory;
    private readonly SubscribedBotFactory $subscribedBotFactory;
    private readonly RemoteBotActivatorMocker $remoteBotActivatorMocker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usernameFactory = $this->resolve(UsernameFactory::class);
        $this->subscribedBotFactory = $this->resolve(SubscribedBotFactory::class);
        $this->remoteBotActivatorMocker = $this->resolve(RemoteBotActivatorMocker::class);
    }

    public function testItThrowsBotValidationExceptionIfInvalidUsernameIsProvided(): void
    {
        $invalidUsername = '';

        $this->expectExceptionObject(
            BotValidationException::fromException(InvalidUsernameException::tooShort($invalidUsername))
        );

        $this
            ->resolve(ActivateBotUseCase::class)
            ->activate($invalidUsername);
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
            ->resolve(ActivateBotUseCase::class)
            ->activate((string)$username);
    }

    public function testItActivatesABot(): void
    {
        $bot = $this->subscribedBotFactory->create();
        $bot->deactivate();

        $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository($bot)
        );

        $this->mock(
            RemoteBotActivator::class,
            fn (MockInterface $mock) => $this
                ->remoteBotActivatorMocker
                ->gets($bot)
                ->mock($mock)
        );

        $this
            ->resolve(ActivateBotUseCase::class)
            ->activate((string)$bot->username());

        $this->assertTrue(
            $bot->isActive()
        );
    }
}
