<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Activate\ActivateEndpointFactory;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Create\CreateEndpointFactory;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Deactivate\DeactivateEndpointFactory;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Delete\DeleteEndpointFactory;

final readonly class BotSubscriptionsResourceFactory
{
    public function __construct(
        private CreateEndpointFactory $createEndpointFactory,
        private DeleteEndpointFactory $deleteEndpointFactory,
        private ActivateEndpointFactory $activateEndpointFactory,
        private DeactivateEndpointFactory $deactivateEndpointFactory,
    ) {}

    public function create(MockHandler $responses, Subscription $subscription): BotSubscriptionsResource
    {
        return new BotSubscriptionsResource(
            createEndpointFactory: $this->createEndpointFactory,
            deleteEndpointFactory: $this->deleteEndpointFactory,
            activateEndpointFactory: $this->activateEndpointFactory,
            deactivateEndpointFactory: $this->deactivateEndpointFactory,
            subscription: $subscription,
            responses: $responses
        );
    }
}
