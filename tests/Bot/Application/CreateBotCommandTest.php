<?php declare(strict_types=1);

namespace Tests\Bot\Application;

use Database\Factories\BotFactory;
use Faker\Generator as Faker;
use Illuminate\Database\Connection;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Hasher;
use olml89\XenforoBots\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use Symfony\Component\Console\Exception\RuntimeException;
use Tests\Common\InteractsWithXenforoApi;
use Tests\PreparesDatabase;
use Tests\TestCase;

final class CreateBotCommandTest extends TestCase
{
    use PreparesDatabase;
    use InteractsWithXenforoApi;

    private const CREATED_USER_OUTPUT_FORMAT = '"userId": %s,'."\n".'    "name": "%s",'."\n".'    "registeredAt": "%s"';
    private const REGISTERED_AT_OUTPUT_FORMAT = 'c';
    private const REGISTERED_AT_DATABASE_FORMAT = 'Y-m-d H:i:s';

    private readonly Faker $faker;
    private readonly Hasher $hasher;
    private readonly Connection $database;
    private readonly BotFactory $botFactory;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->migrate();
        $this->setUpXenforoApi();

        $this->faker = $this->app->get(Faker::class);
        $this->hasher = $this->app->get(Hasher::class);
        $this->database = $this->app->get(Connection::class);
        $this->botFactory = $this->app->get(BotFactory::class);
    }

    protected function tearDown(): void
    {
        $this->resetMigrations();

        parent::tearDown();
    }

    public function test_input_without_name_throws_runtime_exception(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "name, password").');

        $this->artisan('bot:create');
    }

    public function test_input_without_password_throws_runtime_exception(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "password").');

        $this->artisan(sprintf('bot:create %s', $this->faker->userName()));
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function test_valid_input_creates_a_new_xenforo_user_and_it_stores_it_as_bot(): void
    {
        $name = $this->faker->userName();
        $password = $this->faker->password();

        $this->requests->append(
            $this->createUserCreatedResponse(
                user_id: $user_id = $this->faker->numberBetween(1),
                register_date_timestamp: $register_date_timestamp = time(),
            )
        );

        $this
            ->artisan(sprintf('bot:create %s %s', $name, $password))
            ->assertSuccessful()
            ->expectsOutputToContain('Bot created successfully')
            ->expectsOutputToContain(
                sprintf(
                    self::CREATED_USER_OUTPUT_FORMAT,
                    $user_id,
                    $name,
                    UnixTimestamp::toDateTimeImmutable($register_date_timestamp)
                        ->format(self::REGISTERED_AT_OUTPUT_FORMAT),
                )
            );

        $this->assertDatabaseCount('bots', 1);

        $this->assertDatabaseHas('bots', [
            'user_id' => $user_id,
            'name' => $name,
            'registered_at' => UnixTimestamp::toDateTimeImmutable($register_date_timestamp)
                ->format(self::REGISTERED_AT_DATABASE_FORMAT),
        ]);

        $this->assertTrue(
            $this->hasher->check(
                password: $password,
                hash: $this->database->getDoctrineConnection()->fetchOne(
                    query: 'select password from bots where user_id = :user_id',
                    params: ['user_id' => $user_id],
                )
            )
        );
    }
}
