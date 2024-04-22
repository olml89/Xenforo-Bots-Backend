<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Delete;

use GuzzleHttp\Handler\MockHandler;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;

final class DeleteEndpointFactory
{
    public function create(MockHandler $responses, Subscription $subscription): DeleteEndpoint
    {
        return new DeleteEndpoint(
            subscription: $subscription,
            responses: $responses,
        );
    }
}
