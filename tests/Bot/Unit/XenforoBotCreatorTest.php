<?php declare(strict_types=1);

namespace Tests\Bot\Unit;

use Faker\Generator as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use olml89\XenforoBots\Bot\Domain\Bot;
use olml89\XenforoBots\Bot\Domain\BotCreationException;
use olml89\XenforoBots\Bot\Infrastructure\BotCreator\XenforoBotCreator;
use olml89\XenforoBots\Common\Domain\ValueObjects\Password\Hasher;
use olml89\XenforoBots\Common\Domain\ValueObjects\UnixTimestamp\UnixTimestamp;
use olml89\XenforoBots\Common\Infrastructure\Xenforo\User\Create\CreateUserRequestData;
use Tests\TestCase;

final class XenforoBotCreatorTest extends TestCase
{
    private readonly XenforoBotCreator $botCreator;
    private readonly MockHandler $requests;
    private readonly Faker $faker;
    private readonly Hasher $hasher;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->app->get(Faker::class);
        $this->hasher = $this->app->get(Hasher::class);
        $this->requests = new MockHandler();

        $this->setUpXenforoApiConsumer();
        $this->botCreator = $this->app->get(XenforoBotCreator::class);
    }

    private function setUpXenforoApiConsumer(): void
    {
        $this->app->singleton(Client::class, function(): Client {
            return new Client(['handler' => HandlerStack::create($this->requests)]);
        });
    }

    private function createUserData(string $username = null, string $password = null): CreateUserRequestData
    {
        return new CreateUserRequestData(
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

        $this->expectException(BotCreationException::class);
        $this->expectExceptionMessage('Error communicating with Server');

        $this->botCreator->create($createUserData->username, $createUserData->password);
    }

    public function test_that_invalid_username_throws_bot_creation_exception(): void
    {
        $createUserData = $this->createUserData(username: '');

        $this->requests->append(
            new Response(
                status: 400,
                body: json_encode([
                    'errors' => [
                        'code' => 'please_enter_valid_name',
                        'message' => 'Please enter a valid name.',
                    ]
                ]),
            )
        );

        $this->expectException(BotCreationException::class);
        $this->expectExceptionMessage('Please enter a valid name.');

        $this->botCreator->create($createUserData->username, $createUserData->password);
    }

    public function test_that_invalid_password_throws_bot_creation_exception(): void
    {
        $createUserData = $this->createUserData(password: '');

        $this->requests->append(
            new Response(
                status: 400,
                body: json_encode([
                    'errors' => [
                        'code' => 'please_enter_valid_password',
                        'message' => 'Please enter a valid password.',
                    ]
                ]),
            )
        );

        $this->expectException(BotCreationException::class);
        $this->expectExceptionMessage('Please enter a valid password.');

        $this->botCreator->create($createUserData->username, $createUserData->password);
    }

    public function test_that_valid_username_and_password_create_a_new_xenforo_user(): void
    {
        $createUserData = $this->createUserData();

        $user_id = $this->faker->numberBetween(1);
        $register_date_timestamp = time();

        $this->requests->append(
            new Response(
                status: 200,
                body: json_encode([
                    'success' => true,
                    'user' => [
                        'user_id' => $user_id,
                        'register_date' => $register_date_timestamp,
                    ]
                ]),
            )
        );

        $bot = $this->botCreator->create($createUserData->username, $createUserData->password);

        $this->assertInstanceOf(Bot::class, $bot);
        $this->assertEquals($user_id, $bot->userId()->toInt());
        $this->assertEquals($createUserData->username, (string)$bot->name());
        $this->assertTrue($bot->password()->check($createUserData->password, $this->hasher));
        $this->assertEquals(UnixTimestamp::fromUnixTimestamp($register_date_timestamp), $bot->registeredAt());
    }
}
