<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Infrastructure;

use Database\Factories\SubscribedBotFactory;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionRemovalException;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotUnsubscriber;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiConnectionException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiInternalServerErrorException;
use Tests\Helpers\XenforoApi\InteractsWithXenforoApi;
use Tests\Helpers\XenforoApi\XenforoApi;
use Tests\TestCase;

final class XenforoBotUnsubscriberTest extends TestCase implements InteractsWithXenforoApi
{
    use XenforoApi;

    private readonly XenforoBotUnsubscriber $xenforoBotUnsubscriber;
    private readonly Bot $bot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->xenforoBotUnsubscriber = $this->resolve(XenforoBotUnsubscriber::class);

        $this->bot = $this
            ->resolve(SubscribedBotFactory::class)
            ->create();
    }

    public function testItThrowsSubscriptionRemovalExceptionIfXenforoApiIsUnreachable(): void
    {
        $connectException = $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->bot->subscription())
            ->delete()
            ->connectException('Error communicating with Server');

        $xenforoApiException = new XenforoApiConnectionException($connectException);

        try {
            $this
                ->xenforoBotUnsubscriber
                ->unsubscribe($this->bot);
        }
        catch (SubscriptionRemovalException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsSubscriptionRemovalExceptionIfXenforoApiReturnsInternalServerError(): void
    {
        $internalServerErrorException = $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->bot->subscription())
            ->delete()
            ->internalServerErrorException(
                errorCode: Str::random(),
                errorMessage: Str::random()
            );

        $xenforoApiException = new XenforoApiInternalServerErrorException($internalServerErrorException);

        try {
            $this
                ->xenforoBotUnsubscriber
                ->unsubscribe($this->bot);
        }
        catch (SubscriptionRemovalException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItDeletesAXenforoBotSubscription(): void
    {
        $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->bot->subscription())
            ->delete()
            ->ok();

        $this->expectNotToPerformAssertions();

        $this
            ->xenforoBotUnsubscriber
            ->unsubscribe($this->bot);
    }
}
