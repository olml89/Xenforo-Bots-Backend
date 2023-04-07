<?php declare(strict_types=1);

namespace olml89\XenforoBots\Common\Infrastructure\Xenforo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonSerializable;
use olml89\XenforoBots\Common\Domain\ValueObjects\Url\Url;
use Psr\Http\Message\ResponseInterface;

final class ApiConsumer
{
    public function __construct(
        private readonly Url $apiUrl,
        private readonly string $apiKey,
        private readonly Client $httpClient,
        private readonly Url $appUrl,
    ) {}

    public function apiUrl(): Url
    {
        return $this->apiUrl;
    }

    public function appUrl(): Url
    {
        return $this->appUrl;
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $endpoint, JsonSerializable $data = null): ResponseInterface
    {
        return $this->httpClient->get(
            (string)$this->apiUrl->withPath($endpoint),
            !is_null($data)? ['form_params' => $data] : [],
        );
    }

    /**
     * @throws GuzzleException
     */
    public function post(string $endpoint, JsonSerializable $data): ResponseInterface
    {
        return $this->httpClient->post(
            (string)$this->apiUrl->withPath($endpoint),
            ['form_params' => $data],
        );
    }
}
