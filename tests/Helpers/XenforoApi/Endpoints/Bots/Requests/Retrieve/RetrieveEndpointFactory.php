<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\Retrieve;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\XenforoBotDataCreator;

final readonly class RetrieveEndpointFactory
{
    public function __construct(
        private XenforoBotDataCreator $xenforoBotDataCreator,
    ) {}

    public function create(MockHandler $responses, Bot $bot): RetrieveEndpoint
    {
        $responseData = $this->xenforoBotDataCreator->bot($bot)->create();

        return new RetrieveEndpoint(
            responseData: $responseData,
            responses: $responses
        );
    }
}
