<?php declare(strict_types=1);

namespace Tests\Bot\Unit;

use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\Password;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotCreator;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\InvalidApiKeyException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\InvalidUnixTimestampException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiConnectionException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiInternalServerErrorException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiUnprocessableEntityException;
use Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\Create\XenforoBotCreationDataCreator;
use Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\XenforoBotDataCreator;
use Tests\Helpers\XenforoApi\InteractsWithXenforoApi;
use Tests\Helpers\XenforoApi\XenforoApi;
use Tests\TestCase;

final class XenforoBotCreatorTest extends TestCase implements InteractsWithXenforoApi
{
    use XenforoApi;

    private XenforoBotCreationDataCreator $xenforoBotCreationDataCreator;
    private XenforoBotDataCreator $xenforoBotDataCreator;
    private XenforoBotCreator $xenforoBotCreator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->xenforoBotCreationDataCreator = $this->resolve(XenforoBotCreationDataCreator::class);
        $this->xenforoBotDataCreator = $this->resolve(XenforoBotDataCreator::class);
        $this->xenforoBotCreator = $this->resolve(XenforoBotCreator::class);
    }

    public function testItThrowsBotCreationExceptionIfXenforoApiIsUnreachable(): void
    {
        $xenforoBotCreationData = $this->xenforoBotCreationDataCreator->create();

        $connectException = $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create($xenforoBotCreationData)
            ->connectException('Error communicating with Server');

        $xenforoApiException = XenforoApiConnectionException::create($connectException);

        try {
            $this->xenforoBotCreator->create(
                Username::create($xenforoBotCreationData->username),
                Password::create($xenforoBotCreationData->password)
            );
        }
        catch (BotCreationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsBotValidationExceptionIfXenforoApiReturnsValidationError(): void
    {
        $xenforoBotCreationData = $this->xenforoBotCreationDataCreator->create();

        $unprocessableEntityException = $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create($xenforoBotCreationData)
            ->unprocessableEntityException(
                errorCode: Str::random(),
                errorMessage: Str::random(),
            );

        $xenforoApiException = XenforoApiUnprocessableEntityException::create($unprocessableEntityException);

        try {
            $this->xenforoBotCreator->create(
                Username::create($xenforoBotCreationData->username),
                Password::create($xenforoBotCreationData->password)
            );
        }
        catch (BotValidationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsBotCreationExceptionIfXenforoApiReturnsInternalServerError(): void
    {
        $xenforoBotCreationData = $this->xenforoBotCreationDataCreator->create();

        $internalServerErrorException = $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create($xenforoBotCreationData)
            ->internalServerErrorException(
                errorCode: Str::random(),
                errorMessage: Str::random()
            );

        $xenforoApiException = XenforoApiInternalServerErrorException::create($internalServerErrorException);

        try {
            $this->xenforoBotCreator->create(
                Username::create($xenforoBotCreationData->username),
                Password::create($xenforoBotCreationData->password)
            );
        }
        catch (BotCreationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsBotValidationExceptionIfAnInvalidBotIdIsReturnedByTheXenforoApi(): void
    {
        $xenforoBotCreationData = $this->xenforoBotCreationDataCreator->create();

        $xenforoBotData = $this
            ->xenforoBotDataCreator
            ->botId(Str::random())
            ->create();

        $valueObjectException = new InvalidUuidException($xenforoBotData->bot_id);

        $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create()
            ->ok($xenforoBotData);

        try {
            $this->xenforoBotCreator->create(
                Username::create($xenforoBotCreationData->username),
                Password::create($xenforoBotCreationData->password)
            );
        }
        catch (BotValidationException $e) {
            $this->assertEquals($valueObjectException, $e->getPrevious());
        }
    }

    public function testItThrowsBotValidationExceptionIfAnInvalidApiKeyIsReturnedByTheXenforoApi(): void
    {
        $xenforoBotCreationData = $this->xenforoBotCreationDataCreator->create();

        $xenforoBotData = $this
            ->xenforoBotDataCreator
            ->apiKey(Str::random())
            ->create();

        $valueObjectException = new InvalidApiKeyException($xenforoBotData->api_key);

        $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create($xenforoBotCreationData)
            ->ok($xenforoBotData);

        $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create()
            ->ok($xenforoBotData);

        try {
            $this->xenforoBotCreator->create(
                Username::create($xenforoBotCreationData->username),
                Password::create($xenforoBotCreationData->password)
            );
        }
        catch (BotValidationException $e) {
            $this->assertEquals($valueObjectException, $e->getPrevious());
        }
    }

    public function testItThrowsBotValidationExceptionIfAnInvalidUnixTimestampIsReturnedByTheXenforoApi(): void
    {
        $xenforoBotCreationData = $this->xenforoBotCreationDataCreator->create();

        $xenforoBotData = $this
            ->xenforoBotDataCreator
            ->createdAt(-10000000000000)
            ->create();

        $valueObjectException = new InvalidUnixTimestampException($xenforoBotData->created_at);

        $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create()
            ->ok($xenforoBotData);

        try {
            $this->xenforoBotCreator->create(
                Username::create($xenforoBotCreationData->username),
                Password::create($xenforoBotCreationData->password)
            );
        }
        catch (BotValidationException $e) {
            $this->assertEquals($valueObjectException, $e->getPrevious());
        }
    }

    public function testItCreatesANewXenforoBotAndReturnsABotResult(): void
    {
        $xenforoBotCreationData = $this->xenforoBotCreationDataCreator->create();
        $xenforoBotData = $this->xenforoBotDataCreator->create();

        $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create()
            ->ok($xenforoBotData);

        $bot = $this->xenforoBotCreator->create(
            Username::create($xenforoBotCreationData->username),
            Password::create($xenforoBotCreationData->password)
        );

        $this->assertEquals(
            $xenforoBotData->bot_id,
            (string)$bot->botId()
        );
        $this->assertEquals(
            $xenforoBotData->api_key,
            (string)$bot->apiKey()
        );
        $this->assertEquals(
            $xenforoBotCreationData->username,
            (string)$bot->username()
        );
        $this->assertEquals(
            $xenforoBotData->created_at,
            $bot->registeredAt()->timestamp()
        );
    }
}
