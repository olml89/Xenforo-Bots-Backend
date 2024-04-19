<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Subscription\Domain\Subscription;
use Tests\Helpers\XenforoApi\Endpoints\Bots\BotsResource;
use Tests\Helpers\XenforoApi\Endpoints\Bots\BotsResourceFactory;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\BotSubscriptionsResource;
use Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\BotSubscriptionsResourceFactory;

final readonly class XenforoApiResponseSimulator
{
    private MockHandler $responses;

    public function __construct(
        private BotsResourceFactory $botsResourceFactory,
        private BotSubscriptionsResourceFactory $botSubscriptionsResourceFactory,
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

    public function botSubscriptions(?Subscription $subscription = null): BotSubscriptionsResource
    {
        return $this->botSubscriptionsResourceFactory->create($this->responses, $subscription);
    }
}
