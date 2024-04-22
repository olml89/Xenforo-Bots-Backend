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
use olml89\XenforoBotsBackend\Bot\Domain\InvalidPasswordException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBotsBackend\Bot\Domain\BotSubscriber;
use Tests\Bot\Fakes\InMemoryBotRepository;
use Tests\Bot\Mocks\BotProviderMocker;
use Tests\Bot\Mocks\BotSubscriberMocker;
use Tests\TestCase;

final class SubscribeBotUseCaseTest extends TestCase
{
    private readonly UsernameFactory $usernameFactory;
    private readonly PasswordFactory $passwordFactory;
    private readonly BotFactory $botFactory;
    private readonly SubscriptionFactory $subscriptionFactory;
    private readonly BotProviderMocker $botProviderMocker;
    private readonly BotSubscriberMocker $botSubscriberMocker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usernameFactory = $this->resolve(UsernameFactory::class);
        $this->passwordFactory = $this->resolve(PasswordFactory::class);
        $this->botFactory = $this->resolve(BotFactory::class);
        $this->subscriptionFactory = $this->resolve(SubscriptionFactory::class);
        $this->botProviderMocker = $this->resolve(BotProviderMocker::class);
        $this->botSubscriberMocker = $this->resolve(BotSubscriberMocker::class);
    }

    public function testItThrowsBotValidationExceptionIfInvalidUsernameIsProvided(): void
    {
        $invalidUsername = '';
        $invalidPassword = '';

        $this->expectExceptionObject(
            new BotValidationException(InvalidUsernameException::empty())
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
        $username = $this->usernameFactory->create();
        $password = $this->passwordFactory->create();

        $alreadyExistingBot = $this
            ->botFactory
            ->username($username)
            ->create();

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
                (string)$username,
                (string)$password
            );
    }

    public function testItThrowsBotValidationExceptionIfInvalidPasswordIsProvided(): void
    {
        $username = $this->usernameFactory->create();
        $invalidPassword = '';

        $this->expectExceptionObject(
            new BotValidationException(new InvalidPasswordException())
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
        $username = $this->usernameFactory->create();
        $password = $this->passwordFactory->create();

        $bot = $this
            ->botFactory
            ->username($username)
            ->create();

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
                ->gets($username, $password)
                ->returns($bot)
                ->mock($mock)
        );

        $this->mock(
            BotSubscriber::class,
            fn (MockInterface $mock) => $this
                ->botSubscriberMocker
                ->gets($bot)
                ->returns($subscription)
                ->mock($mock)
        );

        $botResult = $this
            ->resolve(SubscribeBotUseCase::class)
            ->subscribe(
                (string)$username,
                (string)$password
            );

        $this->assertNotNull(
            $botRepository->getByUsername($username)
        );
        $this->assertEquals(
            $expectedBotResult,
            $botResult
        );
    }
}
