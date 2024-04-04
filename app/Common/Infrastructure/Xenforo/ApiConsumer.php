<?php declare(strict_types=1);

namespace olml89\XenforoBotsBackend\Common\Infrastructure\Xenforo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonSerializable;
use olml89\XenforoBotsBackend\Common\Domain\ValueObjects\Url\Url;
use Psr\Http\Message\ResponseInterface;

final class ApiConsumer
{
    private const API_PREFIX = '/api';

    private readonly Url $apiUrl;
    private readonly Client $httpClient;

    public function __construct(string $apiKey, Url $apiUrl, array $config = [])
    {
        $this->apiUrl = $apiUrl;

        $defaultConfig = [
            'base_uri' => (string)$apiUrl,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'XF-Api-Key' => $apiKey,
            ],
            'http_errors' => true,
        ];

        $this->httpClient = new Client($defaultConfig + $config);
    }

    public function apiUrl(): Url
    {
        return $this->apiUrl;
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $endpoint, array $parameters = [], array $headers = []): ResponseInterface
    {
        return $this->httpClient->get(
            sprintf(self::API_PREFIX.$endpoint, ...$parameters),
            [
                'headers' => $headers,
            ]
        );
    }

    /**
     * @throws GuzzleException
     */
    public function post(string $endpoint, JsonSerializable $data, array $headers = []): ResponseInterface
    {
        return $this->httpClient->post(
            self::API_PREFIX.$endpoint,
            [
                'headers' => $headers,
                'form_params' => $data,
            ]
        );
    }

    /**
     * @throws GuzzleException
     */
    public function delete(string $endpoint, array $parameters = [], array $headers = []): ResponseInterface
    {
        return $this->httpClient->delete(
            sprintf(self::API_PREFIX.$endpoint, ...$parameters),
            [
                'headers' => $headers,
            ]
        );
    }
}
