<?php declare(strict_types=1);

namespace Tests\Bot\Unit;

use Database\Factories\BotFactory;
use olml89\XenforoBotsBackend\Bot\Application\Create\CreateBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\BotAlreadyExistsException;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreator;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidPasswordException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use Tests\Bot\Fakes\InMemoryBotRepository;
use Tests\Bot\Fakes\TestBotCreator;
use Tests\TestCase;

final class CreateBotUseCaseTest extends TestCase
{
    private readonly BotFactory $botFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->botFactory = $this->resolve(BotFactory::class);
    }

    public function testItThrowsBotValidationExceptionIfInvalidUsernameIsProvided(): void
    {
        $invalidUsername = '';

        $this->expectExceptionObject(new BotValidationException(InvalidUsernameException::empty()));

        $this
            ->resolve(CreateBotUseCase::class)
            ->create($invalidUsername, fake()->password());
    }

    public function testItThrowsBotValidationExceptionIfInvalidPasswordIsProvided(): void
    {
        $invalidPassword = '';

        $this->expectExceptionObject(new BotValidationException(new InvalidPasswordException()));

        $this
            ->resolve(CreateBotUseCase::class)
            ->create(fake()->userName(), $invalidPassword);
    }

    public function testItThrowsBotAlreadyExistsExceptionIfABotWithAProvidedUsernameAlreadyExists(): void
    {
        $alreadyExistingBot = $this->botFactory->create();
        $password = fake()->password();

        $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository($alreadyExistingBot)
        );

        $this->expectExceptionObject(
            BotAlreadyExistsException::username($alreadyExistingBot->username())
        );

        $this
            ->resolve(CreateBotUseCase::class)
            ->create((string)$alreadyExistingBot->username(), $password);
    }

    public function testItSavesACreatedBotAndReturnsABotResult(): void
    {
        $username = fake()->userName();
        $password = fake()->password();

        /** @var BotRepository $botRepository */
        $botRepository = $this->app->instance(
            BotRepository::class,
            new InMemoryBotRepository()
        );

        $this->app->instance(
            BotCreator::class,
            $this->app[TestBotCreator::class]
        );

        $botResult = $this
            ->resolve(CreateBotUseCase::class)
            ->create($username, $password);

        $this->assertNotNull(
            $botRepository->getByUsername(Username::create($username))
        );
        $this->assertEquals(
            $username,
            $botResult->username
        );
    }
}
