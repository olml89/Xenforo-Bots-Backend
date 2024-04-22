<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Create;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotSubscriptionCreationData;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\XenforoBotSubscriptionDataCreator;

final readonly class CreateEndpointFactory
{
    public function __construct(
        private XenforoBotSubscriptionCreationDataCreator $xenforoBotSubscriptionCreationDataCreator,
        private XenforoBotSubscriptionDataCreator $xenforoBotSubscriptionDataCreator,
    ) {}

    public function create(MockHandler $responses, Subscription $subscription, ?XenforoBotSubscriptionCreationData $requestData): CreateEndpoint
    {
        $requestData ??= $this->xenforoBotSubscriptionCreationDataCreator->create();

        $responseData = $this
            ->xenforoBotSubscriptionDataCreator
            ->xenforoBotSubscriptionCreationData($requestData)
            ->subscription($subscription)
            ->create();

        return new CreateEndpoint(
            responseData: $responseData,
            bot: $subscription->bot(),
            responses: $responses,
            requestData: $requestData
        );
    }
}
