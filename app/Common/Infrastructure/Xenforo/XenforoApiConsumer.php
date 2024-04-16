<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use JsonSerializable;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\ApiKey\ApiKey;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiException;
use olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo\Exceptions\XenforoApiExceptionMapper;
use Psr\Http\Message\ResponseInterface;

final readonly class XenforoApiConsumer
{
    private Client $httpClient;
    private XenforoApiExceptionMapper $xenforoApiExceptionMapper;

    public function __construct(Url $apiUrl, ApiKey $superUserApiKey, ?HandlerStack $handlerStack = null)
    {
        $handlerStack ??= HandlerStack::create();

        $this->httpClient = new Client([
            'base_uri' => (string)$apiUrl,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'XF-Api-Key' => (string)$superUserApiKey,
            ],
            'http_errors' => true,
            'handler' => $handlerStack,
        ]);

        $this->xenforoApiExceptionMapper = new XenforoApiExceptionMapper();
    }

    /**
     * @throws XenforoApiException
     */
    public function get(string $endpoint, array $query = [], array $headers = []): ResponseInterface
    {
        try {
            return $this->httpClient->get(
                uri: $endpoint,
                options: [
                    'headers' => $headers,
                    'query' => $query,
                ],
            );
        }
        catch (GuzzleException $e) {
            throw $this->xenforoApiExceptionMapper->map($e);
        }
    }

    /**
     * @throws XenforoApiException
     */
    public function post(string $endpoint, JsonSerializable $data, array $headers = []): ResponseInterface
    {
        try {
            return $this->httpClient->post(
                uri: $endpoint,
                options: [
                    'headers' => $headers,
                    'form_params' => $data,
                ],
            );
        }
        catch (GuzzleException $e) {
            throw $this->xenforoApiExceptionMapper->map($e);
        }
    }

    /**
     * @throws XenforoApiException
     */
    public function delete(string $endpoint, array $query = [], array $headers = []): ResponseInterface
    {
        try {
            return $this->httpClient->delete(
                uri: $endpoint,
                options: [
                    'headers' => $headers,
                    'query' => $query,
                ]
            );
        }
        catch (GuzzleException $e) {
            throw $this->xenforoApiExceptionMapper->map($e);
        }
    }
}
