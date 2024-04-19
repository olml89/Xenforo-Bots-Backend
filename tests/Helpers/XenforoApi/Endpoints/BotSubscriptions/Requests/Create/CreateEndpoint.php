<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\BotSubscriptions\Requests\Create;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Subscription\Infrastructure\Xenforo\XenforoBotSubscriptionCreationData;
use olml89\XenforoBotsBackend\Subscription\Infrastructure\Xenforo\XenforoBotSubscriptionData;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tests\Helpers\XenforoApi\Endpoints\Endpoint;
use Tests\Helpers\XenforoApi\Endpoints\ValidatesInput;

final readonly class CreateEndpoint extends Endpoint
{
    use ValidatesInput;

    private const string METHOD = 'POST';
    private const string URI = 'bots/%s/subscriptions';

    public function __construct(
        private XenforoBotSubscriptionData $responseData,
        Bot $bot,
        MockHandler $responses,
        XenforoBotSubscriptionCreationData $requestData,
    ) {
        parent::__construct(
            responses: $responses,
            method: self::METHOD,
            uri: sprintf(self::URI, $bot->botId()),
            requestData: $requestData,
            headers: [
                'XF-Api-Key' => (string)$bot->botId(),
            ]
        );
    }

    public function ok(?XenforoBotSubscriptionData $responseData = null): Response
    {
        $responseData ??= $this->responseData;

        $this->responses->append(
            $response = new Response(
                status: SymfonyResponse::HTTP_OK,
                body: json_encode([
                    'success' => true,
                    'botSubscription' => [
                        'bot_subscription_id' => $responseData->bot_subscription_id,
                        'webhook' => $responseData->webhook,
                        'platform_api_key' => $responseData->platform_api_key,
                        'is_active' => $responseData->is_active,
                        'subscribed_at' => $responseData->subscribed_at,
                    ],
                ])
            )
        );

        return $response;
    }
}
