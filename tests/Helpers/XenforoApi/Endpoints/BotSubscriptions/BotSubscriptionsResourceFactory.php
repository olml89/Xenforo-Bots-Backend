<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Subscription\Domain\Subscription;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Create\CreateEndpointFactory;

final readonly class BotSubscriptionsResourceFactory
{
    public function __construct(
        private CreateEndpointFactory $createEndpointFactory,
    ) {}

    public function create(MockHandler $responses, Subscription $subscription): BotSubscriptionsResource
    {
        return new BotSubscriptionsResource(
            createEndpointFactory: $this->createEndpointFactory,
            subscription: $subscription,
            responses: $responses
        );
    }
}
