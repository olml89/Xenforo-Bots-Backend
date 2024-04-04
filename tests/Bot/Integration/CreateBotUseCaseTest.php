<?php declare(strict_types=1);

namespace Tests\Bot\Integration;

use Database\Factories\BotFactory;
use Faker\Generator as Faker;
use Mockery\MockInterface;
use olml89\XenforoBotsBackend\Bot\Application\Create\CreateBotUseCase;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreator;
use olml89\XenforoBotsBackend\Bot\Domain\BotRepository;
use Tests\PreparesDatabase;
use Tests\TestCase;

final class CreateBotUseCaseTest extends TestCase
{
    use PreparesDatabase;

    private readonly BotFactory $botFactory;
    private readonly Faker $faker;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->migrate();

        $this->botFactory = $this->app->get(BotFactory::class);
        $this->faker = $this->app->get(Faker::class);
    }

    protected function tearDown(): void
    {
        $this->resetMigrations();

        parent::tearDown();
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function setUpCreateBotUseCase(Bot $bot): CreateBotUseCase
    {
        $botCreator = $this->mock(BotCreator::class, function(MockInterface $mock) use($bot): void {
            $mock->shouldReceive('create')->once()->andReturn($bot);
        });

        return new CreateBotUseCase(
            botCreator: $botCreator,
            botRepository: $this->app->get(BotRepository::class),
        );
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \olml89\XenforoBotsBackend\Bot\Domain\BotCreationException
     * @throws \olml89\XenforoBotsBackend\Bot\Domain\BotStorageException
     * @throws \ReflectionException
     */
    public function test_that_user_created_in_xenforo_is_stored_as_bot(): void
    {
        $password = $this->faker->password();
        $bot = $this->botFactory->create(password: $password);
        $createBotUseCase = $this->setUpCreateBotUseCase($bot);

        $createBotResult = $createBotUseCase->create((string)$bot->name(), $password);

        $this->assertEquals((string)$bot->id(), $createBotResult->id);
        $this->assertEquals($bot->userId()->toInt(), $createBotResult->user_id);
        $this->assertEquals((string)$bot->name(), $createBotResult->name);
        $this->assertEquals($bot->registeredAt()->format('c'), $createBotResult->registered_at);
        $this->assertEquals(null, $createBotResult->subscription);
        $this->assertDatabaseCount('bots', 1);
        $this->assertDatabaseHas('bots', ['id' => (string)$bot->id()]);
    }
}
