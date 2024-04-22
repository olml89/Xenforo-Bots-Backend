<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Application;

use Database\Factories\SubscribedBotFactory;
use Database\Factories\ValueObjects\UsernameFactory;
use Mockery\MockInterface;
use olml89\XenforoBotsBackend\Bot\Application\Deactivate\DeactivateBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\RemoteBotDeactivator;
use olml89\XenforoBotsBackend\Bot\Domain\BotNotFoundException;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;
use Tests\Bot\Fakes\InMemoryBotRepository;
use Tests\Bot\Mocks\RemoteBotDeactivatorMocker;
use Tests\TestCase;

final class DeactivateBotUseCaseTest extends TestCase
{
    private readonly UsernameFactory $usernameFactory;
    private readonly SubscribedBotFactory $subscribedBotFactory;
    private readonly RemoteBotDeactivatorMocker $remoteBotDeactivatorMocker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usernameFactory = $this->resolve(UsernameFactory::class);
        $this->subscribedBotFactory = $this->resolve(SubscribedBotFactory::class);
        $this->remoteBotDeactivatorMocker = $this->resolve(RemoteBotDeactivatorMocker::class);
    }

    public function testItThrowsBotValidationExceptionIfInvalidUsernameIsProvided(): void
    {
        $invalidUsername = '';

        $this->expectExceptionObject(
            new BotValidationException(InvalidUsernameException::empty())
        );

        $this
            ->resolve(DeactivateBotUseCase::class)
            ->deactivate($invalidUsername);
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
            ->resolve(DeactivateBotUseCase::class)
            ->deactivate((string)$username);
    }

    public function testItActivatesABot(): void
    {
        $bot = $this->subscribedBotFactory->create();
        $bot->activate();

        $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository($bot)
        );

        $this->mock(
            RemoteBotDeactivator::class,
            fn (MockInterface $mock) => $this
                ->remoteBotDeactivatorMocker
                ->gets($bot)
                ->mock($mock)
        );

        $this
            ->resolve(DeactivateBotUseCase::class)
            ->deactivate((string)$bot->username());

        $this->assertFalse(
            $bot->isActive()
        );
    }
}
