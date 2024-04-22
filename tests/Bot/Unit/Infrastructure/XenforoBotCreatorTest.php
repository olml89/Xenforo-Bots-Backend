<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Infrastructure;

use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\BotCreationException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\Password;
use olml89\XenforoBotsBackend\Bot\Domain\Username;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotCreationData;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotCreator;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\InvalidApiKeyException;
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

    private XenforoBotCreator $xenforoBotCreator;
    private XenforoBotDataCreator $xenforoBotDataCreator;
    private XenforoBotCreationData $xenforoBotCreationData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->xenforoBotCreator = $this->resolve(XenforoBotCreator::class);
        $this->xenforoBotDataCreator = $this->resolve(XenforoBotDataCreator::class);

        $this->xenforoBotCreationData = $this
            ->resolve(XenforoBotCreationDataCreator::class)
            ->create();
    }

    public function testItThrowsBotCreationExceptionIfXenforoApiIsUnreachable(): void
    {
        $connectException = $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create($this->xenforoBotCreationData)
            ->connectException('Error communicating with Server');

        $xenforoApiException = new XenforoApiConnectionException($connectException);

        try {
            $this->xenforoBotCreator->create(
                Username::create($this->xenforoBotCreationData->username),
                Password::create($this->xenforoBotCreationData->password)
            );
        }
        catch (BotCreationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsBotValidationExceptionIfXenforoApiReturnsValidationError(): void
    {
        $unprocessableEntityException = $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create($this->xenforoBotCreationData)
            ->unprocessableEntityException(
                errorCode: Str::random(),
                errorMessage: Str::random(),
            );

        $xenforoApiException = new XenforoApiUnprocessableEntityException($unprocessableEntityException);

        try {
            $this->xenforoBotCreator->create(
                Username::create($this->xenforoBotCreationData->username),
                Password::create($this->xenforoBotCreationData->password)
            );
        }
        catch (BotValidationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsBotCreationExceptionIfXenforoApiReturnsInternalServerError(): void
    {
        $internalServerErrorException = $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create($this->xenforoBotCreationData)
            ->internalServerErrorException(
                errorCode: Str::random(),
                errorMessage: Str::random()
            );

        $xenforoApiException = new XenforoApiInternalServerErrorException($internalServerErrorException);

        try {
            $this->xenforoBotCreator->create(
                Username::create($this->xenforoBotCreationData->username),
                Password::create($this->xenforoBotCreationData->password)
            );
        }
        catch (BotCreationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsBotValidationExceptionIfAnInvalidBotIdIsReturnedByTheXenforoApi(): void
    {
        $xenforoBotData = $this
            ->xenforoBotDataCreator
            ->botId(Str::random())
            ->create();

        $valueObjectException = new InvalidUuidException($xenforoBotData->bot_id);

        $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create($this->xenforoBotCreationData)
            ->ok($xenforoBotData);

        try {
            $this->xenforoBotCreator->create(
                Username::create($this->xenforoBotCreationData->username),
                Password::create($this->xenforoBotCreationData->password)
            );
        }
        catch (BotValidationException $e) {
            $this->assertEquals($valueObjectException, $e->getPrevious());
        }
    }

    public function testItThrowsBotValidationExceptionIfAnInvalidApiKeyIsReturnedByTheXenforoApi(): void
    {
        $xenforoBotData = $this
            ->xenforoBotDataCreator
            ->apiKey(Str::random())
            ->create();

        $valueObjectException = new InvalidApiKeyException($xenforoBotData->api_key);

        $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create($this->xenforoBotCreationData)
            ->ok($xenforoBotData);

        try {
            $this->xenforoBotCreator->create(
                Username::create($this->xenforoBotCreationData->username),
                Password::create($this->xenforoBotCreationData->password)
            );
        }
        catch (BotValidationException $e) {
            $this->assertEquals($valueObjectException, $e->getPrevious());
        }
    }

    public function testItCreatesANewXenforoBot(): void
    {
        $xenforoBotData = $this->xenforoBotDataCreator->create();

        $this
            ->xenforoApiResponseSimulator
            ->bots()
            ->create()
            ->ok($xenforoBotData);

        $bot = $this->xenforoBotCreator->create(
            Username::create($this->xenforoBotCreationData->username),
            Password::create($this->xenforoBotCreationData->password)
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
            $this->xenforoBotCreationData->username,
            (string)$bot->username()
        );
    }
}
