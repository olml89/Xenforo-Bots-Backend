<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Infrastructure;

use Database\Factories\BotFactory;
use Database\Factories\ValueObjects\PasswordFactory;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotProvisionException;
use olml89\XenforoBotsBackend\Bot\Domain\BotValidationException;
use olml89\XenforoBotsBackend\Bot\Domain\Password;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotProvider;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\InvalidApiKeyException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiConnectionException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiInternalServerErrorException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiUnprocessableEntityException;
use Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\XenforoBotDataCreator;
use Tests\Helpers\XenforoApi\InteractsWithXenforoApi;
use Tests\Helpers\XenforoApi\XenforoApi;
use Tests\TestCase;

final class XenforoBotProviderTest extends TestCase implements InteractsWithXenforoApi
{
    use XenforoApi;

    private readonly XenforoBotProvider $xenforoBotProvider;
    private readonly XenforoBotDataCreator $xenforoBotDataCreator;
    private readonly Bot $bot;
    private readonly Password $password;

    protected function setUp(): void
    {
        parent::setUp();

        $this->xenforoBotProvider = $this->resolve(XenforoBotProvider::class);
        $this->xenforoBotDataCreator = $this->resolve(XenforoBotDataCreator::class);

        $this->bot = $this
            ->resolve(BotFactory::class)
            ->create();

        $this->password = $this
            ->resolve(PasswordFactory::class)
            ->create();
    }

    public function testItThrowsBotProvisionExceptionIfXenforoApiIsUnreachable(): void
    {
        $connectException = $this
            ->xenforoApiResponseSimulator
            ->bots($this->bot)
            ->create()
            ->connectException('Error communicating with Server');

        $xenforoApiException = new XenforoApiConnectionException($connectException);

        try {
            $this->xenforoBotProvider->provide(
                $this->bot->username(),
                $this->password
            );
        }
        catch (BotProvisionException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsBotValidationExceptionIfXenforoApiReturnsValidationError(): void
    {
        $unprocessableEntityException = $this
            ->xenforoApiResponseSimulator
            ->bots($this->bot)
            ->create()
            ->unprocessableEntityException(
                errorCode: Str::random(),
                errorMessage: Str::random(),
            );

        $xenforoApiException = new XenforoApiUnprocessableEntityException($unprocessableEntityException);

        try {
            $this->xenforoBotProvider->provide(
                $this->bot->username(),
                $this->password
            );
        }
        catch (BotValidationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsBotProvisionExceptionIfXenforoApiReturnsInternalServerError(): void
    {
        $internalServerErrorException = $this
            ->xenforoApiResponseSimulator
            ->bots($this->bot)
            ->create()
            ->internalServerErrorException(
                errorCode: Str::random(),
                errorMessage: Str::random()
            );

        $xenforoApiException = new XenforoApiInternalServerErrorException($internalServerErrorException);

        try {
            $this->xenforoBotProvider->provide(
                $this->bot->username(),
                $this->password
            );
        }
        catch (BotProvisionException $e) {
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
            ->bots($this->bot)
            ->create()
            ->ok($xenforoBotData);

        try {
            $this->xenforoBotProvider->provide(
                $this->bot->username(),
                $this->password
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
            ->bots($this->bot)
            ->create()
            ->ok($xenforoBotData);

        try {
            $this->xenforoBotProvider->provide(
                $this->bot->username(),
                $this->password
            );
        }
        catch (BotValidationException $e) {
            $this->assertEquals($valueObjectException, $e->getPrevious());
        }
    }

    public function testItProvidesANewXenforoBot(): void
    {
        $this
            ->xenforoApiResponseSimulator
            ->bots($this->bot)
            ->create()
            ->ok();

        $bot = $this->xenforoBotProvider->provide(
            $this->bot->username(),
            $this->password
        );

        $this->assertEquals(
            $this->bot,
            $bot
        );
    }

    public function testItProvidesAnExistingXenforoBot(): void
    {
        $this
            ->xenforoApiResponseSimulator
            ->bots($this->bot)
            ->create()
            ->conflict();

        $this
            ->xenforoApiResponseSimulator
            ->bots($this->bot)
            ->retrieve()
            ->ok();

        $bot = $this->xenforoBotProvider->provide(
            $this->bot->username(),
            $this->password
        );

        $this->assertEquals(
            $this->bot,
            $bot
        );
    }
}
