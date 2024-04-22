<?php declare(strict_types=1);

namespace Tests\Helpers\XenforoApi\Endpoints;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\Response;
use olml89\XenforoBotsBackend\Common\Domain\JsonSerializable;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

abstract readonly class Endpoint
{
    protected MockHandler $responses;
    protected GuzzleRequest $request;

    public function __construct(
        MockHandler $responses,
        string $method,
        string $uri,
        ?JsonSerializable $requestData = null,
        array $headers = [],
    ) {
        $this->responses = $responses;

        $this->request = new GuzzleRequest(
            method: $method,
            uri: $uri,
            headers: $headers,
            body: is_null($requestData) ? null : (string)$requestData,
        );
    }

    public function connectException(string $message): ConnectException
    {
        $this->responses->append(
            $connectException = new ConnectException(
                message: $message,
                request: $this->request,
            )
        );

        return $connectException;
    }

    public function internalServerErrorException(string $errorCode, string $errorMessage, array $params = []): RequestException
    {
        $this->responses->append(
            $internalServerErrorException = new RequestException(
                message: '',
                request: $this->request,
                response: new Response(
                    status: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR,
                    body: json_encode([
                        'errors' => [
                            [
                                'code' => $errorCode,
                                'message' => $errorMessage,
                                'params' => $params,
                            ],
                        ],
                    ]),
                )
            )
        );

        return $internalServerErrorException;
    }
}
