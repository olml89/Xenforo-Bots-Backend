<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\Retrieve;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use olml89\XenforoBotsBackend\Bot\Domain\Bot;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotData;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tests\Helpers\XenforoApi\Endpoints\Endpoint;

final readonly class RetrieveEndpoint extends Endpoint
{
    private const string METHOD = 'GET';
    private const string URI = 'bots/%s';

    public function __construct(
        private XenforoBotData $responseData,
        MockHandler $responses,
        Bot $bot,
    ) {
        parent::__construct(
            responses: $responses,
            method: self::METHOD,
            uri: sprintf(self::URI, $bot->botId())
        );
    }

    public function ok(?XenforoBotData $responseData = null): Response
    {
        $responseData ??= $this->responseData;

        $this->responses->append(
            $response = new Response(
                status: SymfonyResponse::HTTP_OK,
                body: json_encode([
                    'success' => true,
                    'bot' => [
                        'ApiKey' => [
                            'api_key' => $responseData->api_key,
                        ],
                        'bot_id' => $responseData->bot_id,
                        'BotSubscriptions' => [],
                        'created_at' => $responseData->created_at,
                    ],
                ])
            )
        );

        return $response;
    }
}
