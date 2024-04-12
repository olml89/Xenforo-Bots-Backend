<?php declare(strict_types=1);

namespace Tests\Bot\Unit;

use Faker\Generator as Faker;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\InvalidUsernameException;
use olml89\XenforoBotsBackend\Bot\Infrastructure\BotCreator\XenforoBotCreator;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotCreationData;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Password\Hasher;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use Tests\Common\InteractsWithXenforoApi;
use Tests\TestCase;

final class XenforoBotCreatorTest extends TestCase
{
    use InteractsWithXenforoApi;

    private readonly XenforoBotCreator $botCreator;
    private readonly Faker $faker;
    private readonly Hasher $hasher;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpXenforoApi();

        $this->faker = $this->app->get(Faker::class);
        $this->hasher = $this->app->get(Hasher::class);
        $this->botCreator = $this->app->get(XenforoBotCreator::class);
    }

    private function createUserData(string $username = null, string $password = null): XenforoBotCreationData
    {
        return new XenforoBotCreationData(
            username: $username ?? $this->faker->userName(),
            password: $password ?? $this->faker->password(),
        );
    }

    public function test_that_unreachable_xenforo_api_throws_bot_creation_exception(): void
    {
        $createUserData = $this->createUserData();

        $this->requests->append(
            new ConnectException(
                message: 'Error communicating with Server',
                request: new Request(method: 'POST', uri: '/api/users', body: json_encode($createUserData)),
            )
        );

        $this->expectException(BotValidationException::class);
        $this->expectExceptionMessage('Error communicating with Server');

        $this->botCreator->create($createUserData->username, $createUserData->password);
    }

    public function test_that_void_username_throws_bot_creation_exception(): void
    {
        $createUserData = $this->createUserData(username: '');

        $this->requests->append(
            $this->createBadRequestResponse(
                code: 'please_enter_valid_name',
                message: 'Please enter a valid name.',
            )
        );

        $this->expectException(BotValidationException::class);
        $this->expectExceptionMessage('Please enter a valid name.');

        $this->botCreator->create($createUserData->username, $createUserData->password);
    }

    public function test_that_too_long_username_throws_bot_creation_exception(): void
    {
        $tooLongUsername = Str::random(51);
        $invalidUsernameException = new InvalidUsernameException($tooLongUsername);
        $createUserData = $this->createUserData(username: $tooLongUsername);

        $this->requests->append(
            $this->createUserCreatedResponse(
                user_id: $this->faker->numberBetween(1),
                register_date_timestamp: time(),
            )
        );

        $this->expectException(BotValidationException::class);
        $this->expectExceptionMessage($invalidUsernameException->getMessage());

        $this->botCreator->create($createUserData->username, $createUserData->password);
    }

    public function test_that_void_password_throws_bot_creation_exception(): void
    {
        $createUserData = $this->createUserData(password: '');

        $this->requests->append(
            $this->createBadRequestResponse(
                code: 'please_enter_valid_password',
                message: 'Please enter a valid password.',
            )
        );

        $this->expectException(BotValidationException::class);
        $this->expectExceptionMessage('Please enter a valid password.');

        $this->botCreator->create($createUserData->username, $createUserData->password);
    }

    public function test_that_valid_username_and_password_create_a_new_xenforo_user(): void
    {
        $createUserData = $this->createUserData();
        $user_id = $this->faker->numberBetween(1);
        $register_date_timestamp = time();

        $this->requests->append(
            $this->createUserCreatedResponse(
                user_id: $user_id,
                register_date_timestamp: $register_date_timestamp,
            )
        );

        $bot = $this->botCreator->create($createUserData->username, $createUserData->password);

        $this->assertInstanceOf(Bot::class, $bot);
        $this->assertEquals($user_id, $bot->userId()->toInt());
        $this->assertEquals($createUserData->username, (string)$bot->name());
        $this->assertTrue($bot->password()->check($createUserData->password, $this->hasher));
        $this->assertEquals(
            UnixTimestamp::fromTimestamp($register_date_timestamp),
            $bot->registeredAt()
        );
    }
}
