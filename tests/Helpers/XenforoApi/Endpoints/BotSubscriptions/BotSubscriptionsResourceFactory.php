<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Activate\ActivateEndpointFactory;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Create\CreateEndpointFactory;

final readonly class BotSubscriptionsResourceFactory
{
    public function __construct(
        private CreateEndpointFactory $createEndpointFactory,
        private ActivateEndpointFactory $activateEndpointFactory,
    ) {}

    public function create(MockHandler $responses, Subscription $subscription): BotSubscriptionsResource
    {
        return new BotSubscriptionsResource(
            createEndpointFactory: $this->createEndpointFactory,
            activateEndpointFactory: $this->activateEndpointFactory,
            subscription: $subscription,
            responses: $responses
        );
    }
}
