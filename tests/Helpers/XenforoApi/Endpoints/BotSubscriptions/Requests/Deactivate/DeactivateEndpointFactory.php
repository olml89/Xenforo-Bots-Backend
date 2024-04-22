<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Deactivate;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;

final class DeactivateEndpointFactory
{
    public function create(MockHandler $responses, Subscription $subscription): DeactivateEndpoint
    {
        return new DeactivateEndpoint(
            subscription: $subscription,
            responses: $responses,
        );
    }
}
