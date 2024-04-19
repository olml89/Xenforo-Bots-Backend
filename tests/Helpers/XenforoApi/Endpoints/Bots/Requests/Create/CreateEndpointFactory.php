<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\Create;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotCreationData;
use Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\XenforoBotDataCreator;

final readonly class CreateEndpointFactory
{
    public function __construct(
        private XenforoBotCreationDataCreator $xenforoBotCreationDataCreator,
        private XenforoBotDataCreator $xenforoBotDataCreator,
    ) {}

    public function create(MockHandler $responses, Bot $bot, ?XenforoBotCreationData $requestData): CreateEndpoint
    {
        $requestData ??= $this->xenforoBotCreationDataCreator->bot($bot)->create();
        $responseData = $this->xenforoBotDataCreator->bot($bot)->create();

        return new CreateEndpoint(
            responseData: $responseData,
            responses: $responses,
            requestData: $requestData
        );
    }
}
