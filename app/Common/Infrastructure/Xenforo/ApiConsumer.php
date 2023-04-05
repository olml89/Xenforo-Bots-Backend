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
    ) { }

    public function apiUrl(): Url
    {
        return $this->apiUrl;
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
