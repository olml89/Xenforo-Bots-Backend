<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Application;

use Database\Factories\SubscribedBotFactory;
use Database\Factories\ValueObjects\PasswordFactory;
use Mockery\MockInterface;
use olml89\XenforoBotsBackend\Bot\Application\Subscribe\SubscribeBotUseCase;
use olml89\XenforoBotsBackend\Bot\Application\Unsubscribe\UnsubscribeBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotAlreadyExistsException;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\RemoteBotUnsubscriber;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\InvalidUsernameException;
use Tests\Bot\Fakes\InMemoryBotRepository;
use Tests\Bot\Mocks\RemoteBotUnsubscriberMocker;
use Tests\TestCase;

final class UnsubscribeBotUseCaseTest extends TestCase
{
    private readonly PasswordFactory $passwordFactory;
    private readonly SubscribedBotFactory $subscribedBotFactory;
    private readonly RemoteBotUnsubscriberMocker $remoteBotUnsubscriberMocker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->passwordFactory = $this->resolve(PasswordFactory::class);
        $this->subscribedBotFactory = $this->resolve(SubscribedBotFactory::class);
        $this->remoteBotUnsubscriberMocker = $this->resolve(RemoteBotUnsubscriberMocker::class);
    }

    public function testItThrowsBotValidationExceptionIfInvalidUsernameIsProvided(): void
    {
        $invalidUsername = '';
        $invalidPassword = '';

        $this->expectExceptionObject(
            BotValidationException::fromException(InvalidUsernameException::tooShort($invalidUsername))
        );

        $this
            ->resolve(SubscribeBotUseCase::class)
            ->subscribe(
                $invalidUsername,
                $invalidPassword
            );
    }

    public function testItThrowsBotAlreadyExistsExceptionIfABotWithAProvidedUsernameAlreadyExists(): void
    {
        $alreadyExistingBot = $this->subscribedBotFactory->create();

        $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository($alreadyExistingBot)
        );

        $this->expectExceptionObject(
            BotAlreadyExistsException::bot($alreadyExistingBot)
        );

        $this
            ->resolve(SubscribeBotUseCase::class)
            ->subscribe(
                (string)$alreadyExistingBot->username(),
                (string)$this->passwordFactory->create()
            );
    }

    public function testItDeletesAnUnsubscribedBot(): void
    {
        $bot = $this->subscribedBotFactory->create();

         /** @var BotRepository $botRepository */
        $botRepository = $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository($bot)
        );

        $this->mock(
            RemoteBotUnsubscriber::class,
            fn (MockInterface $mock) => $this
                ->remoteBotUnsubscriberMocker
                ->gets($bot)
                ->mock($mock)
        );

        $this
            ->resolve(UnsubscribeBotUseCase::class)
            ->unsubscribe((string)$bot->username());

        $this->assertNull(
            $botRepository->getByUsername($bot->username())
        );
    }
}
