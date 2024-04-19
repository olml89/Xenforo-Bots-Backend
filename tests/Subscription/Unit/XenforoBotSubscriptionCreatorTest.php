<?php declare(strict_types=1);

namespace Tests\Subscription\Unit;

use Database\Factories\BotFactory;
use Illuminate\Support\Str;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\UnixTimestamp\InvalidUnixTimestampException;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiConnectionException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiInternalServerErrorException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiUnprocessableEntityException;
use olml89\XenforoBotsBackend\Subscription\Domain\Subscription;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionCreationException;
use olml89\XenforoBotsBackend\Subscription\Domain\SubscriptionValidationException;
use olml89\XenforoBotsBackend\Subscription\Infrastructure\Xenforo\XenforoBotSubscriptionCreator;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\XenforoBotSubscriptionDataCreator;
use Tests\Helpers\XenforoApi\InteractsWithXenforoApi;
use Tests\Helpers\XenforoApi\XenforoApi;
use Tests\TestCase;

final class XenforoBotSubscriptionCreatorTest extends TestCase implements InteractsWithXenforoApi
{
    use XenforoApi;

    private readonly XenforoBotSubscriptionCreator $xenforoBotSubscriptionCreator;
    private readonly XenforoBotSubscriptionDataCreator $xenforoBotSubscriptionDataCreator;
    private readonly Subscription $subscription;

    protected function setUp(): void
    {
        parent::setUp();

        $this->xenforoBotSubscriptionCreator = $this->resolve(XenforoBotSubscriptionCreator::class);
        $this->xenforoBotSubscriptionDataCreator = $this->resolve(XenforoBotSubscriptionDataCreator::class);

        $this->subscription = $this
            ->resolve(BotFactory::class)
            ->create()
            ->subscription();
    }

    public function testItThrowsSubscriptionCreationExceptionIfXenforoApiIsUnreachable(): void
    {
        $connectException = $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->subscription)
            ->create()
            ->connectException('Error communicating with Server');

        $xenforoApiException = XenforoApiConnectionException::create($connectException);

        try {
            $this
                ->xenforoBotSubscriptionCreator
                ->create($this->subscription->bot());
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

        $xenforoApiException = XenforoApiUnprocessableEntityException::create($unprocessableEntityException);

        try {
            $this
                ->xenforoBotSubscriptionCreator
                ->create($this->subscription->bot());
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

        $xenforoApiException = XenforoApiInternalServerErrorException::create($internalServerErrorException);

        try {
            $this
                ->xenforoBotSubscriptionCreator
                ->create($this->subscription->bot());
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

        $valueObjectException = new InvalidUuidException($xenforoBotSubscriptionData->bot_id);

        $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->subscription)
            ->create()
            ->ok($xenforoBotSubscriptionData);

        try {
            $this
                ->xenforoBotSubscriptionCreator
                ->create($this->subscription->bot());
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

        $valueObjectException = new InvalidUnixTimestampException($xenforoBotSubscriptionData->subscribed_at);

        $this
            ->xenforoApiResponseSimulator
            ->botSubscriptions($this->subscription)
            ->create()
            ->ok($xenforoBotSubscriptionData);

        try {
            $this
                ->xenforoBotSubscriptionCreator
                ->create($this->subscription->bot());
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
            ->create($this->subscription->bot());

        $this->assertEquals(
            $this->subscription,
            $subscription
        );
    }
}
