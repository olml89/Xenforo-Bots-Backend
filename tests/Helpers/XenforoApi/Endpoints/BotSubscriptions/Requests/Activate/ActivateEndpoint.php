<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Activate;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use olml89\XenforoBotsBackend\Bot\Domain\Subscription\Subscription;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tests\Helpers\XenforoApi\Endpoints\Endpoint;

final readonly class ActivateEndpoint extends Endpoint
{
    private const string METHOD = 'POST';
    private const string URI = 'bots/%s/subscriptions/%s/activate';

    public function __construct(Subscription $subscription, MockHandler $responses)
    {
        parent::__construct(
            responses: $responses,
            method: self::METHOD,
            uri: sprintf(
                self::URI,
                $subscription->bot()->botId(),
                $subscription->subscriptionId()
            )
        );
    }

    public function ok(): Response
    {
        $this->responses->append(
            $response = new Response(
                status: SymfonyResponse::HTTP_OK,
                body: json_encode([
                    'success' => true,
                ])
            )
        );

        return $response;
    }
}
