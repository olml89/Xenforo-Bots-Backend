<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotSubscriptionCreationData;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Activate\ActivateEndpoint;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Activate\ActivateEndpointFactory;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Create\CreateEndpoint;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Create\CreateEndpointFactory;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Deactivate\DeactivateEndpoint;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Deactivate\DeactivateEndpointFactory;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Delete\DeleteEndpoint;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Delete\DeleteEndpointFactory;
use Tests\Helpers\XenforoApi\Endpoints\Resource;

final readonly class BotSubscriptionsResource extends Resource
{
    public function __construct(
        private CreateEndpointFactory $createEndpointFactory,
        private DeleteEndpointFactory $deleteEndpointFactory,
        private ActivateEndpointFactory $activateEndpointFactory,
        private DeactivateEndpointFactory $deactivateEndpointFactory,
        private Subscription $subscription,
        MockHandler $responses,
    ) {
        parent::__construct($responses);
    }

    public function create(?XenforoBotSubscriptionCreationData $requestData = null): CreateEndpoint
    {
        return $this->createEndpointFactory->create(
            responses: $this->responses,
            subscription: $this->subscription,
            requestData: $requestData
        );
    }

    public function delete(): DeleteEndpoint
    {
        return $this->deleteEndpointFactory->create(
            responses: $this->responses,
            subscription: $this->subscription
        );
    }

    public function activate(): ActivateEndpoint
    {
        return $this->activateEndpointFactory->create(
            responses: $this->responses,
            subscription: $this->subscription
        );
    }

    public function deactivate(): DeactivateEndpoint
    {
        return $this->deactivateEndpointFactory->create(
            responses: $this->responses,
            subscription: $this->subscription
        );
    }
}
