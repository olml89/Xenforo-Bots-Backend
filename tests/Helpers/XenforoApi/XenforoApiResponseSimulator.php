<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use Tests\Helpers\XenforoApi\Endpoints\Bots\BotsResource;
use Tests\Helpers\XenforoApi\Endpoints\Bots\BotsResourceFactory;

final readonly class XenforoApiResponseSimulator
{
    private MockHandler $responses;

    public function __construct(
        private BotsResourceFactory $botsResourceFactory,
    ) {
        $this->responses = new MockHandler();
    }

    public function responses(): MockHandler
    {
        return $this->responses;
    }

    public function bots(?Bot $bot = null): BotsResource
    {
        return $this->botsResourceFactory->create($this->responses, $bot);
    }
}
