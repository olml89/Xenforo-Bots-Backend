<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\Bots;

use Database\Factories\BotFactory;
use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\Create\CreateEndpointFactory;
use Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\Retrieve\RetrieveEndpointFactory;

final readonly class BotsResourceFactory
{
    public function __construct(
        private CreateEndpointFactory $createEndpointFactory,
        private RetrieveEndpointFactory $retrieveEndpointFactory,
        private BotFactory $botFactory,
    ) {}

    public function create(MockHandler $responses, ?Bot $bot = null): BotsResource
    {
        return new BotsResource(
            createEndpointFactory: $this->createEndpointFactory,
            retrieveEndpointFactory: $this->retrieveEndpointFactory,
            bot: $bot ?? $this->botFactory->create(),
            responses: $responses
        );
    }
}
