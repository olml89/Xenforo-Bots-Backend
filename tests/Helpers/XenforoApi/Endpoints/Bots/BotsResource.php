<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\Bots;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotCreationData;
use Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\Create\CreateEndpoint;
use Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\Create\CreateEndpointFactory;
use Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\Retrieve\RetrieveEndpoint;
use Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\Retrieve\RetrieveEndpointFactory;
use Tests\Helpers\XenforoApi\Endpoints\Resource;

final readonly class BotsResource extends Resource
{
    public function __construct(
        private CreateEndpointFactory $createEndpointFactory,
        private RetrieveEndpointFactory $retrieveEndpointFactory,
        private Bot $bot,
        MockHandler $responses,
    ) {
        parent::__construct($responses);
    }

    public function create(?XenforoBotCreationData $requestData = null): CreateEndpoint
    {
        return $this->createEndpointFactory->create(
            responses: $this->responses,
            bot: $this->bot,
            requestData: $requestData
        );
    }

    public function retrieve(): RetrieveEndpoint
    {
        return $this->retrieveEndpointFactory->create(
            responses: $this->responses,
            bot: $this->bot
        );
    }
}
