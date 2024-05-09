<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Application;

use Database\Factories\BotFactory;
use Database\Factories\SubscriptionFactory;
use Database\Factories\ValueObjects\PasswordFactory;
use Database\Factories\ValueObjects\UsernameFactory;
use Mockery\MockInterface;
use olml89\XenforoBotsBackend\Bot\Application\BotResult;
use olml89\XenforoBotsBackend\Bot\Application\Subscribe\SubscribeBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotAlreadyExistsException;
use olml89\XenforoBotsBackend\Bot\Domain\BotProvider;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\EqualsUsernameSpecification;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidPasswordException;
use olml89\XenforoBotsBackend\Bot\Domain\RemoteBotSubscriber;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Username\InvalidUsernameException;
use Tests\Bot\Fakes\InMemoryBotRepository;
use Tests\Bot\Mocks\BotProviderMocker;
use Tests\Bot\Mocks\RemoteBotSubscriberMocker;
use Tests\TestCase;

final class SubscribeBotUseCaseTest extends TestCase
{
    private readonly UsernameFactory $usernameFactory;
    private readonly PasswordFactory $passwordFactory;
    private readonly BotFactory $botFactory;
    private readonly SubscriptionFactory $subscriptionFactory;
    private readonly BotProviderMocker $botProviderMocker;
    private readonly RemoteBotSubscriberMocker $remoteBotSubscriberMocker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usernameFactory = $this->resolve(UsernameFactory::class);
        $this->passwordFactory = $this->resolve(PasswordFactory::class);
        $this->botFactory = $this->resolve(BotFactory::class);
        $this->subscriptionFactory = $this->resolve(SubscriptionFactory::class);
        $this->botProviderMocker = $this->resolve(BotProviderMocker::class);
        $this->remoteBotSubscriberMocker = $this->resolve(RemoteBotSubscriberMocker::class);
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
        $alreadyExistingBot = $this->botFactory->create();

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

    public function testItThrowsBotValidationExceptionIfInvalidPasswordIsProvided(): void
    {
        $username = $this->usernameFactory->create();
        $invalidPassword = '';

        $this->expectExceptionObject(
            BotValidationException::fromException(new InvalidPasswordException())
        );

        $this
            ->resolve(SubscribeBotUseCase::class)
            ->subscribe(
                (string)$username,
                $invalidPassword
            );
    }

    public function testItSavesACreatedBotAndReturnsABotResult(): void
    {
        $bot = $this->botFactory->create();
        $password = $this->passwordFactory->create();

        $subscription = $this
            ->subscriptionFactory
            ->bot($bot)
            ->create();

        $expectedBotResult = new BotResult(
            (clone $bot)->subscribe($subscription)
        );

        /** @var BotRepository $botRepository */
        $botRepository = $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository()
        );

        $this->mock(
            BotProvider::class,
            fn(MockInterface $mock) => $this
                ->botProviderMocker
                ->gets($bot->username(), $password)
                ->returns($bot)
                ->mock($mock)
        );

        $this->mock(
            RemoteBotSubscriber::class,
            fn (MockInterface $mock) => $this
                ->remoteBotSubscriberMocker
                ->gets($bot)
                ->returns($subscription)
                ->mock($mock)
        );

        $botResult = $this
            ->resolve(SubscribeBotUseCase::class)
            ->subscribe(
                (string)$bot->username(),
                (string)$password
            );

        $this->assertNotNull(
            $botRepository->getOneBy(
                new EqualsUsernameSpecification($bot->username())
            )
        );
        $this->assertEquals(
            $expectedBotResult,
            $botResult
        );
    }
}
