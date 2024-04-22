<?php declare(strict_types=1);

namespace Tests\Bot\Unit\Infrastructure;

use Database\Factories\SubscriptionFactory;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionCreationException;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\SubscriptionValidationException;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotSubscriber;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\InvalidUnixTimestampException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiConnectionException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiInternalServerErrorException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiUnprocessableEntityException;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\XenforoBotSubscriptionDataCreator;
use Tests\Helpers\XenforoApi\InteractsWithXenforoApi;
use Tests\Helpers\XenforoApi\XenforoApi;
use Tests\TestCase;

final class XenforoBotSubscriptionCreatorTest extends TestCase implements InteractsWithXenforoApi
{
    use XenforoApi;

    private readonly XenforoBotSubscriber $xenforoBotSubscriptionCreator;
    private readonly XenforoBotSubscriptionDataCreator $xenforoBotSubscriptionDataCreator;
    private readonly Subscription $subscription;

    protected function setUp(): void
    {
        parent::setUp();

        $this->xenforoBotSubscriptionCreator = $this->resolve(XenforoBotSubscriber::class);
        $this->xenforoBotSubscriptionDataCreator = $this->resolve(XenforoBotSubscriptionDataCreator::class);

        $this->subscription = $this
            ->resolve(SubscriptionFactory::class)
            ->create();
    }

    public function testItThrowsSubscriptionCreationExceptionIfXenforoApiIsUnreachable(): void
    {
        $connectException = $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->subscription)
            ->create()
            ->connectException('Error communicating with Server');

        $xenforoApiException = new XenforoApiConnectionException($connectException);

        try {
            $this
                ->xenforoBotSubscriptionCreator
                ->subscribe($this->subscription->bot());
        }
        catch (SubscriptionCreationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsSubscriptionValidationExceptionIfXenforoApiReturnsValidationError(): void
    {
        $unprocessableEntityException = $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->subscription)
            ->create()
            ->unprocessableEntityException(
                errorCode: Str::random(),
                errorMessage: Str::random(),
            );

        $xenforoApiException = new XenforoApiUnprocessableEntityException($unprocessableEntityException);

        try {
            $this
                ->xenforoBotSubscriptionCreator
                ->subscribe($this->subscription->bot());
        }
        catch (SubscriptionValidationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsSubscriptionCreationExceptionIfXenforoApiReturnsInternalServerError(): void
    {
        $internalServerErrorException = $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->subscription)
            ->create()
            ->internalServerErrorException(
                errorCode: Str::random(),
                errorMessage: Str::random()
            );

        $xenforoApiException = new XenforoApiInternalServerErrorException($internalServerErrorException);

        try {
            $this
                ->xenforoBotSubscriptionCreator
                ->subscribe($this->subscription->bot());
        }
        catch (SubscriptionCreationException $e) {
            $this->assertEquals($xenforoApiException, $e->getPrevious());
        }
    }

    public function testItThrowsSubscriptionValidationExceptionIfAnInvalidBotSubscriptionIdIsReturnedByTheXenforoApi(): void
    {
        $xenforoBotSubscriptionData = $this
            ->xenforoBotSubscriptionDataCreator
            ->botSubscriptionId(Str::random())
            ->create();

        $valueObjectException = new InvalidUuidException($xenforoBotSubscriptionData->bot_subscription_id);

        $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->subscription)
            ->create()
            ->ok($xenforoBotSubscriptionData);

        try {
            $this
                ->xenforoBotSubscriptionCreator
                ->subscribe($this->subscription->bot());
        }
        catch (SubscriptionValidationException $e) {
            $this->assertEquals($valueObjectException, $e->getPrevious());
        }
    }

    public function testItThrowsBotValidationExceptionIfAnInvalidUnixTimestampIsReturnedByTheXenforoApi(): void
    {
        $xenforoBotSubscriptionData = $this
            ->xenforoBotSubscriptionDataCreator
            ->subscribedAt(-10000000000000)
            ->create();

        $valueObjectException = InvalidUnixTimestampException::invalid();

        $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->subscription)
            ->create()
            ->ok($xenforoBotSubscriptionData);

        try {
            $this
                ->xenforoBotSubscriptionCreator
                ->subscribe($this->subscription->bot());
        }
        catch (SubscriptionValidationException $e) {
            $this->assertEquals($valueObjectException, $e->getPrevious());
        }
    }

    public function testItCreatesANewXenforoBotSubscription(): void
    {
        $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->subscription)
            ->create()
            ->ok();

        $subscription = $this
            ->xenforoBotSubscriptionCreator
            ->subscribe($this->subscription->bot());

        $this->assertEquals(
            $this->subscription,
            $subscription
        );
    }
}
