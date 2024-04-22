<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Infrastructure;

use Database\Factories\SubscribedBotFactory;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\BotActivationException;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotActivator;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiConnectionException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiInternalServerErrorException;
use Tests\Helpers\XenforoApi\InteractsWithXenforoApi;
use Tests\Helpers\XenforoApi\XenforoApi;
use Tests\TestCase;

final class XenforoBotActivatorTest extends TestCase implements InteractsWithXenforoApi
{
    use XenforoApi;

    private readonly XenforoBotActivator $xenforoBotActivator;
    private readonly Bot $bot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->xenforoBotActivator = $this->resolve(XenforoBotActivator::class);
        $this->bot = $this->resolve(SubscribedBotFactory::class)->create();
    }

    public function testItThrowsBotActivationExceptionIfXenforoApiIsUnreachable(): void
    {
        $connectException = $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->bot->subscription())
            ->activate()
            ->connectException('Error communicating with Server');

        $xenforoApiException = new XenforoApiConnectionException($connectException);

        try {
            $this
                ->xenforoBotActivator
                ->activate($this->bot);
        }
        catch (BotActivationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsBotActivationExceptionIfXenforoApiReturnsInternalServerError(): void
    {
        $internalServerErrorException = $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->bot->subscription())
            ->activate()
            ->internalServerErrorException(
                errorCode: Str::random(),
                errorMessage: Str::random()
            );

        $xenforoApiException = new XenforoApiInternalServerErrorException($internalServerErrorException);

        try {
            $this
                ->xenforoBotActivator
                ->activate($this->bot);
        }
        catch (BotActivationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItActivatesAXenforoBotSubscription(): void
    {
        $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->bot->subscription())
            ->activate()
            ->ok();

        $this->expectNotToPerformAssertions();

        $this
            ->xenforoBotActivator
            ->activate($this->bot);
    }
}
