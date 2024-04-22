<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Activate;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Subscription\Domain\Subscription;

final class ActivateEndpointFactory
{
    public function create(MockHandler $responses, Subscription $subscription): ActivateEndpoint
    {
        return new ActivateEndpoint(
            subscription: $subscription,
            responses: $responses,
        );
    }
}
