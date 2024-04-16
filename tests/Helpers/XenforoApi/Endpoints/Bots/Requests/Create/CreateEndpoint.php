<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints\Bots\Requests\Create;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotCreationData;
use olml89\XenforoBotsBackend\Bot\Infrastructure\Xenforo\XenforoBotData;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tests\Helpers\XenforoApi\Endpoints\Endpoint;
use Tests\Helpers\XenforoApi\Endpoints\ValidatesInput;

final readonly class CreateEndpoint extends Endpoint
{
    use ValidatesInput;

    private const string METHOD = 'POST';
    private const string URI = 'bots';

    public function __construct(
        private XenforoBotData $responseData,
        MockHandler $responses,
        XenforoBotCreationData $requestData,
    ) {
        parent::__construct(
            responses: $responses,
            method: self::METHOD,
            uri: self::URI,
            requestData: $requestData
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
                        'user_id' => $responseData->user_id,
                    ],
                ])
            )
        );

        return $response;
    }
}
