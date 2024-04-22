<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Infrastructure;

use Database\Factories\SubscribedBotFactory;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotDeactivationException;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotDeactivator;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiConnectionException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiInternalServerErrorException;
use Tests\Helpers\XenforoApi\InteractsWithXenforoApi;
use Tests\Helpers\XenforoApi\XenforoApi;
use Tests\TestCase;

final class XenforoBotDeactivatorTest extends TestCase implements InteractsWithXenforoApi
{
    use XenforoApi;

    private readonly XenforoBotDeactivator $xenforoBotDeactivator;
    private readonly Bot $bot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->xenforoBotDeactivator = $this->resolve(XenforoBotDeactivator::class);
        $this->bot = $this->resolve(SubscribedBotFactory::class)->create();
    }

    public function testItThrowsBotActivationExceptionIfXenforoApiIsUnreachable(): void
    {
        $connectException = $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->bot->subscription())
            ->deactivate()
            ->connectException('Error communicating with Server');

        $xenforoApiException = new XenforoApiConnectionException($connectException);

        try {
            $this
                ->xenforoBotDeactivator
                ->deactivate($this->bot);
        }
        catch (BotDeactivationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsBotActivationExceptionIfXenforoApiReturnsInternalServerError(): void
    {
        $internalServerErrorException = $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->bot->subscription())
            ->deactivate()
            ->internalServerErrorException(
                errorCode: Str::random(),
                errorMessage: Str::random()
            );

        $xenforoApiException = new XenforoApiInternalServerErrorException($internalServerErrorException);

        try {
            $this
                ->xenforoBotDeactivator
                ->deactivate($this->bot);
        }
        catch (BotDeactivationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItDeactivatesAXenforoBotSubscription(): void
    {
        $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->bot->subscription())
            ->deactivate()
            ->ok();

        $this->expectNotToPerformAssertions();

        $this
            ->xenforoBotDeactivator
            ->deactivate($this->bot);
    }
}
